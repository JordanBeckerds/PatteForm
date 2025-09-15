<?php
include '../includes/dashboard/actions/actualite_actions.php';
$stmt = $pdo->query("SELECT * FROM actualite ORDER BY date_publication DESC");
$actualites = $stmt->fetchAll();
?>

<?php
// Search term
$search = $_GET['search'] ?? '';

// Sort order toggle (safe)
$sort_order = strtoupper($_GET['sort'] ?? 'DESC');
if($sort_order !== 'ASC' && $sort_order !== 'DESC') $sort_order = 'DESC';
$next_sort = $sort_order === 'ASC' ? 'DESC' : 'ASC';

// Column for sorting (hardcoded and backticks)
$sort_column = '`date_publication`';  // keep exact DB column name, no strtoupper

// Prepare query
$sql = "SELECT * FROM actualite WHERE titre LIKE :search ORDER BY $sort_column $sort_order";
$stmt = $pdo->prepare($sql);
$stmt->execute(['search' => "%$search%"]);
$actualites = $stmt->fetchAll();
?>


<h1 class="text-3xl font-bold mb-6 text-[<?=$color_tertiary?>]">Actualités</h1>

<!-- Form Add/Edit -->
<form method="POST" class="bg-white p-6 rounded shadow mb-6">
    <input type="hidden" name="id" value="<?= $edit_data['id'] ?? '' ?>">
    
    <div class="mb-4">
        <label class="block font-semibold mb-1">Titre</label>
        <input type="text" name="titre" value="<?= $edit_data['titre'] ?? '' ?>" class="w-full border p-2 rounded" required>
    </div>
    
    <div class="mb-4">
        <label class="block font-semibold mb-1">Description</label>
        <textarea name="description" class="w-full border p-2 rounded" required><?= $edit_data['description'] ?? '' ?></textarea>
    </div>
    
    <div class="mb-4 flex gap-4">
        <div>
            <label class="block font-semibold mb-1">Date</label>
            <input type="date" name="date_publication" value="<?= $edit_data['date_publication'] ?? '' ?>" class="border p-2 rounded" required>
        </div>
        <div>
            <label class="block font-semibold mb-1">Image URL</label>
            <input type="text" name="img" value="<?= $edit_data['img'] ?? '' ?>" class="border p-2 rounded">
        </div>
    </div>
    
    <div class="flex gap-2">
        <button type="submit" name="<?= isset($edit_data) ? 'update_actualite' : 'add_actualite' ?>" 
                class="bg-[<?=$color_tertiary?>] text-white px-4 py-2 rounded hover:bg-[<?=$color_secondary?>] transition">
            <?= isset($edit_data) ? 'Update' : 'Add' ?>
        </button>
        
        <?php if(isset($edit_data)): ?>
            <a href="dashboard.php?page=actualite" 
               class="bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500 transition">
                Cancel
            </a>
        <?php endif; ?>
    </div>
</form>

<div class="mb-4 flex flex-wrap gap-4 items-center">
    <!-- Search -->
    <form method="GET" class="flex gap-2">
        <input type="hidden" name="page" value="actualite">
        <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" 
               placeholder="Search by title..." 
               class="border p-2 rounded w-64">
        <button type="submit" class="bg-[<?=$color_tertiary?>] text-white px-4 py-2 rounded hover:bg-[<?=$color_secondary?>] transition">Search</button>
    </form>

    <!-- Sort Button -->
    <a href="dashboard.php?page=actualite&search=<?= urlencode($search) ?>&sort=<?= $next_sort ?>" 
       class="bg-[<?=$color_tertiary?>] text-white px-4 py-2 rounded hover:bg-[<?=$color_secondary?>] transition flex items-center gap-1">
        Sort by Date <?= $sort_order === 'ASC' ? '↑' : '↓' ?>
    </a>
</div>


<!-- Table -->
<div class="overflow-x-auto bg-white p-4 rounded shadow">
<table class="w-full table-auto">
    <thead>
        <tr class="bg-[<?=$color_primary?>] text-white">
            <th class="px-4 py-2">ID</th>
            <th class="px-4 py-2">Titre</th>
            <th class="px-4 py-2">Description</th>
            <th class="px-4 py-2">Date</th>
            <th class="px-4 py-2">Image</th>
            <th class="px-4 py-2">Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($actualites as $act): ?>
        <tr class="border-b hover:bg-gray-50">
            <td class="px-4 py-2"><?= $act['id'] ?></td>
            <td class="px-4 py-2"><?= $act['titre'] ?></td>
            <td class="px-4 py-2"><?= substr($act['description'],0,50) ?>...</td>
            <td class="px-4 py-2"><?= $act['date_publication'] ?></td>
            <td class="px-4 py-2"><?= $act['img'] ?></td>
            <td class="px-4 py-2 flex gap-2">
                <a href="?page=actualite&edit=<?= $act['id'] ?>" 
                   class="bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600">Edit</a>
                <a href="?page=actualite&delete=<?= $act['id'] ?>" 
                   onclick="return confirm('Delete?')" 
                   class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</div>

