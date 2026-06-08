<?php
// Included by public/dashboard.php (which already requires auth + config)
require_once dirname(__DIR__, 2) . '/dashboard/actions/actualite_actions.php';

$search = $_GET['search'] ?? '';
$sort_order = strtoupper($_GET['sort'] ?? 'DESC');
if (!in_array($sort_order, ['ASC', 'DESC'], true)) $sort_order = 'DESC';
$next_sort = $sort_order === 'ASC' ? 'DESC' : 'ASC';

$stmt = $pdo->prepare('SELECT * FROM actualite WHERE titre LIKE :search ORDER BY date_publication ' . $sort_order);
$stmt->execute(['search' => '%' . $search . '%']);
$actualites = $stmt->fetchAll();

$ct  = htmlspecialchars($color_tertiary,  ENT_QUOTES, 'UTF-8');
$cs  = htmlspecialchars($color_secondary, ENT_QUOTES, 'UTF-8');
$cp  = htmlspecialchars($color_primary,   ENT_QUOTES, 'UTF-8');
?>

<h1 class="text-3xl font-bold mb-6" style="color:<?= $ct ?>">Actualit&#233;s</h1>

<!-- Add / Edit form -->
<form method="POST" class="bg-white p-6 rounded shadow mb-6">
  <input type="hidden" name="id" value="<?= (int)($edit_data['id'] ?? 0) ?>">
  <div class="mb-4">
    <label class="block font-semibold mb-1">Titre</label>
    <input type="text" name="titre"
           value="<?= htmlspecialchars($edit_data['titre'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
           class="w-full border p-2 rounded" required>
  </div>
  <div class="mb-4">
    <label class="block font-semibold mb-1">Description</label>
    <textarea name="description" class="w-full border p-2 rounded" required
    ><?= htmlspecialchars($edit_data['description'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
  </div>
  <div class="mb-4 flex gap-4">
    <div>
      <label class="block font-semibold mb-1">Date</label>
      <input type="date" name="date_publication"
             value="<?= htmlspecialchars($edit_data['date_publication'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
             class="border p-2 rounded" required>
    </div>
    <div>
      <label class="block font-semibold mb-1">Image URL</label>
      <input type="text" name="img"
             value="<?= htmlspecialchars($edit_data['img'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
             class="border p-2 rounded">
    </div>
  </div>
  <div class="flex gap-2">
    <button type="submit"
            name="<?= isset($edit_data) ? 'update_actualite' : 'add_actualite' ?>"
            class="text-white px-4 py-2 rounded transition"
            style="background-color:<?= $ct ?>">
      <?= isset($edit_data) ? 'Mettre &#224; jour' : 'Ajouter' ?>
    </button>
    <?php if (isset($edit_data)): ?>
      <a href="dashboard.php?page=actualite"
         class="bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500 transition">Annuler</a>
    <?php endif; ?>
  </div>
</form>

<!-- Search + sort -->
<div class="mb-4 flex flex-wrap gap-4 items-center">
  <form method="GET" class="flex gap-2">
    <input type="hidden" name="page" value="actualite">
    <input type="text" name="search"
           value="<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>"
           placeholder="Rechercher par titre..."
           class="border p-2 rounded w-64">
    <button type="submit" class="text-white px-4 py-2 rounded transition" style="background-color:<?= $ct ?>">Rechercher</button>
  </form>
  <a href="dashboard.php?page=actualite&amp;search=<?= urlencode($search) ?>&amp;sort=<?= urlencode($next_sort) ?>"
     class="text-white px-4 py-2 rounded transition" style="background-color:<?= $ct ?>">
    Trier par date <?= $sort_order === 'ASC' ? '&#8593;' : '&#8595;' ?>
  </a>
</div>

<!-- Table -->
<div class="overflow-x-auto bg-white p-4 rounded shadow">
  <table class="w-full table-auto">
    <thead>
      <tr style="background-color:<?= $cp ?>" class="text-white">
        <th class="px-4 py-2">ID</th>
        <th class="px-4 py-2">Titre</th>
        <th class="px-4 py-2">Description</th>
        <th class="px-4 py-2">Date</th>
        <th class="px-4 py-2">Image</th>
        <th class="px-4 py-2">Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($actualites as $act): ?>
        <tr class="border-b hover:bg-gray-50">
          <td class="px-4 py-2"><?= (int)$act['id'] ?></td>
          <td class="px-4 py-2"><?= htmlspecialchars($act['titre'], ENT_QUOTES, 'UTF-8') ?></td>
          <td class="px-4 py-2"><?= htmlspecialchars(mb_substr($act['description'], 0, 50), ENT_QUOTES, 'UTF-8') ?>&#8230;</td>
          <td class="px-4 py-2"><?= htmlspecialchars($act['date_publication'], ENT_QUOTES, 'UTF-8') ?></td>
          <td class="px-4 py-2"><?= htmlspecialchars($act['img'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
          <td class="px-4 py-2 flex gap-2">
            <a href="?page=actualite&amp;edit=<?= (int)$act['id'] ?>"
               class="bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600">Modifier</a>
            <a href="?page=actualite&amp;delete=<?= (int)$act['id'] ?>"
               onclick="return confirm('Supprimer ?')"
               class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">Supprimer</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
