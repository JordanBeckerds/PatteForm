<?php
// Initialisation
$search = $_GET['search'] ?? '';
$filter_espece = $_GET['espece'] ?? '';
$sort_order = strtoupper($_GET['sort'] ?? 'DESC');
if($sort_order !== 'ASC' && $sort_order !== 'DESC') $sort_order = 'DESC';
$next_sort = $sort_order === 'ASC' ? 'DESC' : 'ASC';

// Récupération pour édition
$edit_data = null;
if(isset($_GET['edit'])){
    $stmt = $pdo->prepare("SELECT * FROM animaux_a_adopter WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $edit_data = $stmt->fetch();
}

// Add / Update
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $nom = $_POST['nom'];
    $espece = $_POST['espece'];
    $race = $_POST['race'];
    $prix = $_POST['prix'];
    $sexe = $_POST['sexe'];
    $age = $_POST['age'];
    $description = $_POST['description'];
    $enfant = isset($_POST['enfant']) ? 1 : 0;
    $chat = isset($_POST['chat']) ? 1 : 0;
    $chien = isset($_POST['chien']) ? 1 : 0;
    $autre = isset($_POST['autre']) ? 1 : 0;
    $categorie = $_POST['categorie'] ?? 'aucune';
    $sos = isset($_POST['sos']) ? 1 : 0;
    $date_arriver = $_POST['date_arriver'];
    $img_urls = $_POST['img_urls'] ?? [];

    if(isset($_POST['update_animal'])){
        $stmt = $pdo->prepare("UPDATE animaux_a_adopter SET nom=?, espece=?, race=?, prix=?, sexe=?, age=?, description=?, enfant=?, chat=?, chien=?, autre=?, categorie=?, sos=?, date_arriver=? WHERE id=?");
        $stmt->execute([$nom,$espece,$race,$prix,$sexe,$age,$description,$enfant,$chat,$chien,$autre,$categorie,$sos,$date_arriver,$_POST['id']]);
        $animal_id = $_POST['id'];

        // Supprimer anciennes images
        $stmt = $pdo->prepare("DELETE FROM photo_chiens WHERE id = ?");
        $stmt->execute([$animal_id]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO animaux_a_adopter (nom, espece, race, prix, sexe, age, description, enfant, chat, chien, autre, categorie, sos, date_arriver) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
        $stmt->execute([$nom,$espece,$race,$prix,$sexe,$age,$description,$enfant,$chat,$chien,$autre,$categorie,$sos,$date_arriver]);
        $animal_id = $pdo->lastInsertId();
    }

    // Ajouter images
    if(!empty($img_urls)){
        $stmt = $pdo->prepare("INSERT INTO photo_chiens (id, img) VALUES (?, ?)");
        foreach($img_urls as $img){
            if(trim($img) !== ''){
                $stmt->execute([$animal_id, $img]);
            }
        }
    }

    header("Location: dashboard.php?page=animaux");
    exit;
}

// Delete
if(isset($_GET['delete'])){
    $stmt = $pdo->prepare("DELETE FROM animaux_a_adopter WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    header("Location: dashboard.php?page=animaux");
    exit;
}

// Fetch animaux avec recherche, filtre et tri
$sql = "SELECT * FROM animaux_a_adopter WHERE 1";
$params = [];
if($search){
    $sql .= " AND nom LIKE :search";
    $params['search'] = "%$search%";
}
if($filter_espece){
    $sql .= " AND espece = :espece";
    $params['espece'] = $filter_espece;
}
$sql .= " ORDER BY `date_arriver` $sort_order";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$animaux = $stmt->fetchAll();
?>

<h1 class="text-3xl font-bold mb-6 text-[<?=$color_tertiary?>]">Gestion des Animaux</h1>

<!-- Formulaire Add/Edit -->
<form method="POST" class="bg-white p-6 rounded shadow mb-6">
    <input type="hidden" name="id" value="<?= $edit_data['id'] ?? '' ?>">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block font-semibold mb-1">Nom</label>
            <input type="text" name="nom" value="<?= $edit_data['nom'] ?? '' ?>" class="w-full border p-2 rounded" required>
        </div>
        <div>
            <label class="block font-semibold mb-1">Espèce</label>
            <select name="espece" class="w-full border p-2 rounded" required>
                <option value="">Choisir...</option>
                <option value="chien" <?= (isset($edit_data) && $edit_data['espece']=='chien')?'selected':'' ?>>Chien</option>
                <option value="chat" <?= (isset($edit_data) && $edit_data['espece']=='chat')?'selected':'' ?>>Chat</option>
                <option value="autre" <?= (isset($edit_data) && $edit_data['espece']=='autre')?'selected':'' ?>>Autre</option>
            </select>
        </div>
        <div>
            <label class="block font-semibold mb-1">Race</label>
            <input type="text" name="race" value="<?= $edit_data['race'] ?? '' ?>" class="w-full border p-2 rounded">
        </div>
        <div>
            <label class="block font-semibold mb-1">Prix</label>
            <input type="number" name="prix" value="<?= $edit_data['prix'] ?? '' ?>" class="w-full border p-2 rounded">
        </div>
        <div>
            <label class="block font-semibold mb-1">Sexe</label>
            <select name="sexe" class="w-full border p-2 rounded">
                <option value="">Choisir...</option>
                <option value="male" <?= (isset($edit_data) && $edit_data['sexe']=='male')?'selected':'' ?>>Mâle</option>
                <option value="femelle" <?= (isset($edit_data) && $edit_data['sexe']=='femelle')?'selected':'' ?>>Femelle</option>
            </select>
        </div>
        <div>
            <label class="block font-semibold mb-1">Âge</label>
            <input type="number" name="age" value="<?= $edit_data['age'] ?? '' ?>" class="w-full border p-2 rounded">
        </div>
        <div class="md:col-span-2">
            <label class="block font-semibold mb-1">Description</label>
            <textarea name="description" class="w-full border p-2 rounded"><?= $edit_data['description'] ?? '' ?></textarea>
        </div>
        <!-- Images -->
        <div class="md:col-span-2">
            <label class="block font-semibold mb-1">Images</label>
            <div id="img-container" class="space-y-2">
                <?php
                if($edit_data){
                    $stmt = $pdo->prepare("SELECT * FROM photo_chiens WHERE id = ?");
                    $stmt->execute([$edit_data['id']]);
                    $photos = $stmt->fetchAll();
                    foreach($photos as $photo){
                        echo '<input type="text" name="img_urls[]" value="'.htmlspecialchars($photo['img']).'" class="w-full border p-2 rounded">';
                    }
                }
                ?>
                <input type="text" name="img_urls[]" placeholder="Nouvelle image URL" class="w-full border p-2 rounded">
            </div>
            <button type="button" onclick="addImgField()" class="mt-2 bg-[<?=$color_tertiary?>] text-white px-4 py-2 rounded hover:bg-[<?=$color_secondary?>] transition">
                Ajouter une image
            </button>
        </div>

        <div class="flex flex-wrap gap-4 mt-2">
            <label class="block items-center">
                <input type="checkbox" name="enfant" value="1" <?= (isset($edit_data) && $edit_data['enfant'])?'checked':'' ?> class="mr-2"> Adapté aux enfants
            </label>
            <label class="inline-flex items-center">
                <input type="checkbox" name="chat" value="1" <?= (isset($edit_data) && $edit_data['chat'])?'checked':'' ?> class="mr-2"> Ami avec les chats
            </label>
            <label class="inline-flex items-center">
                <input type="checkbox" name="chien" value="1" <?= (isset($edit_data) && $edit_data['chien'])?'checked':'' ?> class="mr-2"> Ami avec les chiens
            </label>
            <label class="inline-flex items-center">
                <input type="checkbox" name="autre" value="1" <?= (isset($edit_data) && $edit_data['autre'])?'checked':'' ?> class="mr-2"> Ami avec autres animaux
            </label>
            <label class="inline-flex items-center">
                <input type="checkbox" name="sos" value="1" <?= (isset($edit_data) && $edit_data['sos'])?'checked':'' ?> class="mr-2"> SOS
            </label>
        </div>
        <div>
            <label class="block font-semibold mb-1">Date d'arrivée</label>
            <input type="date" name="date_arriver" value="<?= $edit_data['date_arriver'] ?? date('Y-m-d') ?>" class="w-full border p-2 rounded">
        </div>
    </div>
    <div class="mt-4 flex gap-2">
        <button type="submit" name="<?= isset($edit_data)?'update_animal':'add_animal' ?>" class="bg-[<?=$color_tertiary?>] text-white px-4 py-2 rounded hover:bg-[<?=$color_secondary?>] transition">
            <?= isset($edit_data)?'Mettre à jour':'Ajouter' ?>
        </button>
        <?php if(isset($edit_data)): ?>
            <a href="dashboard.php?page=animaux" class="bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500 transition">Annuler</a>
        <?php endif; ?>
    </div>
</form>

<script>
function addImgField(){
    const container = document.getElementById('img-container');
    const input = document.createElement('input');
    input.type = 'text';
    input.name = 'img_urls[]';
    input.placeholder = 'Nouvelle image URL';
    input.className = 'w-full border p-2 rounded';
    container.appendChild(input);
}
</script>

<!-- Barre de recherche, filtre et tri -->
<div class="mb-4 flex flex-wrap gap-4 items-center">
    <form method="GET" class="flex gap-2">
        <input type="hidden" name="page" value="animaux">
        <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Rechercher par nom..." class="border p-2 rounded">
        <button type="submit" class="bg-[<?=$color_tertiary?>] text-white px-4 py-2 rounded hover:bg-[<?=$color_secondary?>] transition">Rechercher</button>
    </form>

    <form method="GET" class="flex gap-2">
        <input type="hidden" name="page" value="animaux">
        <select name="espece" onchange="this.form.submit()" class="border p-2 rounded">
            <option value="">Filtrer par espèce</option>
            <option value="chien" <?= $filter_espece=='chien'?'selected':'' ?>>Chien</option>
            <option value="chat" <?= $filter_espece=='chat'?'selected':'' ?>>Chat</option>
            <option value="autre" <?= $filter_espece=='autre'?'selected':'' ?>>Autre</option>
        </select>
    </form>

    <a href="dashboard.php?page=animaux&search=<?= urlencode($search) ?>&espece=<?= urlencode($filter_espece) ?>&sort=<?= $next_sort ?>" 
       class="bg-[<?=$color_tertiary?>] text-white px-4 py-2 rounded hover:bg-[<?=$color_secondary?>] transition flex items-center gap-1">
        Trier par date d'arrivée <?= $sort_order === 'ASC' ? '↑' : '↓' ?>
    </a>
</div>

<!-- Tableau -->
<table class="w-full border-collapse border border-gray-200 bg-white text-left rounded shadow">
    <thead class="bg-[<?=$color_primary?>] text-white">
        <tr>
            <th class="p-2 border">Image</th>
            <th class="p-2 border">Nom</th>
            <th class="p-2 border">Espèce</th>
            <th class="p-2 border">Race</th>
            <th class="p-2 border">Âge</th>
            <th class="p-2 border">Prix</th>
            <th class="p-2 border">Arrivée</th>
            <th class="p-2 border">Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($animaux as $dog): ?>
            <tr class="hover:bg-gray-100">
                <td class="p-2 border flex gap-2">
                    <?php
                    $stmt = $pdo->prepare("SELECT img FROM photo_chiens WHERE id = ?");
                    $stmt->execute([$dog['id']]);
                    $photos = $stmt->fetchAll();
                    if($photos){
                        foreach($photos as $photo){
                            echo '<img src="'.htmlspecialchars($photo['img']).'" class="h-16 w-16 object-cover rounded">';
                        }
                    } else {
                        echo '<span class="text-gray-400">Aucune</span>';
                    }
                    ?>
                </td>
                <td class="p-2 border"><?= htmlspecialchars($dog['nom']) ?></td>
                <td class="p-2 border"><?= htmlspecialchars($dog['espece']) ?></td>
                <td class="p-2 border"><?= htmlspecialchars($dog['race']) ?></td>
                <td class="p-2 border"><?= htmlspecialchars($dog['age']) ?></td>
                <td class="p-2 border"><?= htmlspecialchars($dog['prix']) ?></td>
                <td class="p-2 border"><?= htmlspecialchars($dog['date_arriver']) ?></td>
                <td class="p-2 border gap-2">
                    <a href="dashboard.php?page=animaux&edit=<?= $dog['id'] ?>" class="bg-[<?=$color_tertiary?>] text-white px-3 py-2 rounded hover:bg-[<?=$color_secondary?>] transition">Modifier</a>
                    <a href="dashboard.php?page=animaux&delete=<?= $dog['id'] ?>" onclick="return confirm('Supprimer cet animal ?')" class="bg-red-500 text-white px-3 py-2 rounded hover:bg-red-600 transition">Supprimer</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>