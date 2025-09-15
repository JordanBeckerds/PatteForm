<?php
// Variables pour recherche et tri
$search = $_GET['search'] ?? '';
$sort_order = strtoupper($_GET['sort'] ?? 'ASC');
if($sort_order !== 'ASC' && $sort_order !== 'DESC') $sort_order = 'ASC';
$next_sort = $sort_order === 'ASC' ? 'DESC' : 'ASC';

// Récupération pour édition
$edit_data = null;
if(isset($_GET['edit'])){
    $stmt = $pdo->prepare("SELECT * FROM homepage_sections WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $edit_data = $stmt->fetch();
}

// Add / Update
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $section_key = $_POST['section_key'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $img_url = $_POST['img_url'];
    $button_text = $_POST['button_text'];
    $button_link = $_POST['button_link'];
    $visible = isset($_POST['visible']) ? 1 : 0;

    if(isset($_POST['update_section'])){
        $stmt = $pdo->prepare("UPDATE homepage_sections SET section_key=?, title=?, description=?, img_url=?, button_text=?, button_link=?, visible=? WHERE id=?");
        $stmt->execute([$section_key, $title, $description, $img_url, $button_text, $button_link, $visible, $_POST['id']]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO homepage_sections (section_key, title, description, img_url, button_text, button_link, visible) VALUES (?,?,?,?,?,?,?)");
        $stmt->execute([$section_key, $title, $description, $img_url, $button_text, $button_link, $visible]);
    }

    header("Location: dashboard.php?page=homepage_sections");
    exit;
}

// Delete
if(isset($_GET['delete'])){
    $stmt = $pdo->prepare("DELETE FROM homepage_sections WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    header("Location: dashboard.php?page=homepage_sections");
    exit;
}

// Fetch sections avec recherche et tri
$sql = "SELECT * FROM homepage_sections WHERE 1";
$params = [];
if($search){
    $sql .= " AND title LIKE :search";
    $params['search'] = "%$search%";
}
$sql .= " ORDER BY visible $sort_order";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$sections = $stmt->fetchAll();
?>

<h1 class="text-3xl font-bold mb-6 text-[<?=$color_tertiary?>]">Gestion des Sections de la Homepage</h1>

<!-- Formulaire Add/Edit -->
<form method="POST" class="bg-white p-6 rounded shadow mb-6">
    <input type="hidden" name="id" value="<?= $edit_data['id'] ?? '' ?>">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block font-semibold mb-1">Clé de section</label>
            <input type="text" name="section_key" value="<?= $edit_data['section_key'] ?? '' ?>" class="w-full border p-2 rounded" required>
        </div>
        <div>
            <label class="block font-semibold mb-1">Titre</label>
            <input type="text" name="title" value="<?= $edit_data['title'] ?? '' ?>" class="w-full border p-2 rounded">
        </div>
        <div class="md:col-span-2">
            <label class="block font-semibold mb-1">Description</label>
            <textarea name="description" class="w-full border p-2 rounded"><?= $edit_data['description'] ?? '' ?></textarea>
        </div>
        <div>
            <label class="block font-semibold mb-1">URL de l'image</label>
            <input type="text" name="img_url" value="<?= $edit_data['img_url'] ?? '' ?>" class="w-full border p-2 rounded">
        </div>
        <div>
            <label class="block font-semibold mb-1">Texte du bouton</label>
            <input type="text" name="button_text" value="<?= $edit_data['button_text'] ?? '' ?>" class="w-full border p-2 rounded">
        </div>
        <div>
            <label class="block font-semibold mb-1">Lien du bouton</label>
            <input type="text" name="button_link" value="<?= $edit_data['button_link'] ?? '' ?>" class="w-full border p-2 rounded">
        </div>
        <div class="flex items-center mt-2">
            <input type="checkbox" name="visible" value="1" <?= (isset($edit_data) && $edit_data['visible'])?'checked':'' ?> class="mr-2">
            <span>Visible</span>
        </div>
    </div>
    <div class="mt-4 flex gap-2">
        <button type="submit" name="<?= isset($edit_data)?'update_section':'add_section' ?>" class="bg-[<?=$color_tertiary?>] text-white px-4 py-2 rounded hover:bg-[<?=$color_secondary?>] transition">
            <?= isset($edit_data)?'Mettre à jour':'Ajouter' ?>
        </button>
        <?php if(isset($edit_data)): ?>
            <a href="dashboard.php?page=homepage_sections" class="bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500 transition">Annuler</a>
        <?php endif; ?>
    </div>
</form>

<!-- Recherche et tri -->
<div class="mb-4 flex flex-wrap gap-4 items-center">
    <form method="GET" class="flex gap-2">
        <input type="hidden" name="page" value="homepage_sections">
        <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Rechercher par titre..." class="border p-2 rounded">
        <button type="submit" class="bg-[<?=$color_tertiary?>] text-white px-4 py-2 rounded hover:bg-[<?=$color_secondary?>] transition">Rechercher</button>
    </form>

    <a href="dashboard.php?page=homepage_sections&search=<?= urlencode($search) ?>&sort=<?= $next_sort ?>" 
       class="bg-[<?=$color_tertiary?>] text-white px-4 py-2 rounded hover:bg-[<?=$color_secondary?>] transition flex items-center gap-1">
        Trier par visibilité <?= $sort_order === 'ASC' ? '↑' : '↓' ?>
    </a>
</div>

<!-- Tableau -->
<table class="w-full border-collapse border border-gray-200 bg-white text-left rounded shadow">
    <thead class="bg-[<?=$color_primary?>] text-white">
        <tr>
            <th class="p-2 border">Clé</th>
            <th class="p-2 border">Titre</th>
            <th class="p-2 border">Description</th>
            <th class="p-2 border">Image</th>
            <th class="p-2 border">Texte bouton</th>
            <th class="p-2 border">Lien bouton</th>
            <th class="p-2 border">Visible</th>
            <th class="p-2 border">Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($sections as $section): ?>
            <tr class="hover:bg-gray-100">
                <td class="p-2 border"><?= htmlspecialchars($section['section_key']) ?></td>
                <td class="p-2 border"><?= htmlspecialchars($section['title']) ?></td>
                <td class="p-2 border"><?= htmlspecialchars($section['description']) ?></td>
                <td class="p-2 border">
                    <?php if($section['img_url']): ?>
                        <img src="<?= htmlspecialchars($section['img_url']) ?>" class="h-16 w-16 object-cover rounded">
                    <?php else: ?>
                        <span class="text-gray-400">Aucune</span>
                    <?php endif; ?>
                </td>
                <td class="p-2 border"><?= htmlspecialchars($section['button_text']) ?></td>
                <td class="p-2 border"><?= htmlspecialchars($section['button_link']) ?></td>
                <td class="p-2 border"><?= $section['visible'] ? '✔' : '❌' ?></td>
                <td class="p-2 border gap-2">
                    <a href="dashboard.php?page=homepage_sections&edit=<?= $section['id'] ?>" class="bg-[<?=$color_tertiary?>] text-white px-3 py-2 rounded hover:bg-[<?=$color_secondary?>] transition">Modifier</a>
                    <a href="dashboard.php?page=homepage_sections&delete=<?= $section['id'] ?>" onclick="return confirm('Supprimer cette section ?')" class="bg-red-500 text-white px-3 py-2 rounded hover:bg-red-600 transition">Supprimer</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>