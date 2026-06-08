<?php
// Equipe management page — included by public/dashboard.php

$edit_data_eq = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom    = trim($_POST['nom']    ?? '');
    $poste  = trim($_POST['poste']  ?? '');
    $photo  = trim($_POST['photo']  ?? '');
    $bio    = trim($_POST['bio']    ?? '');

    if (isset($_POST['update_equipe'])) {
        $stmt = $pdo->prepare('UPDATE equipe SET nom=?, poste=?, photo=?, bio=? WHERE id=?');
        $stmt->execute([$nom, $poste, $photo, $bio, (int)($_POST['id'] ?? 0)]);
    } else {
        $stmt = $pdo->prepare('INSERT INTO equipe (nom, poste, photo, bio) VALUES (?,?,?,?)');
        $stmt->execute([$nom, $poste, $photo, $bio]);
    }
    header('Location: dashboard.php?page=equipe');
    exit;
}

if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare('DELETE FROM equipe WHERE id=?');
    $stmt->execute([(int)$_GET['delete']]);
    header('Location: dashboard.php?page=equipe');
    exit;
}

if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare('SELECT * FROM equipe WHERE id=?');
    $stmt->execute([(int)$_GET['edit']]);
    $edit_data_eq = $stmt->fetch() ?: null;
}

$membres = $pdo->query('SELECT * FROM equipe ORDER BY id ASC')->fetchAll();

$ct = htmlspecialchars($color_tertiary,  ENT_QUOTES, 'UTF-8');
$cp = htmlspecialchars($color_primary,   ENT_QUOTES, 'UTF-8');
?>

<h1 class="text-3xl font-bold mb-6" style="color:<?= $ct ?>">Équipe</h1>

<form method="POST" class="bg-white p-6 rounded shadow mb-6">
  <input type="hidden" name="id" value="<?= (int)($edit_data_eq['id'] ?? 0) ?>">
  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
      <label class="block font-semibold mb-1">Nom</label>
      <input type="text" name="nom" required
             value="<?= htmlspecialchars($edit_data_eq['nom'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
             class="w-full border p-2 rounded">
    </div>
    <div>
      <label class="block font-semibold mb-1">Poste</label>
      <input type="text" name="poste"
             value="<?= htmlspecialchars($edit_data_eq['poste'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
             class="w-full border p-2 rounded">
    </div>
    <div>
      <label class="block font-semibold mb-1">Photo URL</label>
      <input type="text" name="photo"
             value="<?= htmlspecialchars($edit_data_eq['photo'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
             class="w-full border p-2 rounded">
    </div>
    <div class="md:col-span-2">
      <label class="block font-semibold mb-1">Bio</label>
      <textarea name="bio" class="w-full border p-2 rounded"
      ><?= htmlspecialchars($edit_data_eq['bio'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
    </div>
  </div>
  <div class="mt-4 flex gap-2">
    <button type="submit"
            name="<?= isset($edit_data_eq) ? 'update_equipe' : 'add_equipe' ?>"
            class="text-white px-4 py-2 rounded transition"
            style="background-color:<?= $ct ?>">
      <?= isset($edit_data_eq) ? 'Mettre &#224; jour' : 'Ajouter' ?>
    </button>
    <?php if (isset($edit_data_eq)): ?>
      <a href="dashboard.php?page=equipe"
         class="bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500 transition">Annuler</a>
    <?php endif; ?>
  </div>
</form>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
  <?php foreach ($membres as $m): ?>
    <div class="bg-white rounded-xl shadow p-4 flex flex-col items-center text-center">
      <?php if (!empty($m['photo'])): ?>
        <img src="<?= htmlspecialchars($m['photo'], ENT_QUOTES, 'UTF-8') ?>"
             alt="<?= htmlspecialchars($m['nom'], ENT_QUOTES, 'UTF-8') ?>"
             class="w-24 h-24 rounded-full object-cover mb-3">
      <?php endif; ?>
      <h3 class="font-bold text-lg"><?= htmlspecialchars($m['nom'], ENT_QUOTES, 'UTF-8') ?></h3>
      <p class="text-gray-500 text-sm"><?= htmlspecialchars($m['poste'] ?? '', ENT_QUOTES, 'UTF-8') ?></p>
      <p class="text-gray-700 text-sm mt-2"><?= htmlspecialchars($m['bio'] ?? '', ENT_QUOTES, 'UTF-8') ?></p>
      <div class="mt-3 flex gap-2">
        <a href="?page=equipe&amp;edit=<?= (int)$m['id'] ?>"
           class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 text-sm">Modifier</a>
        <a href="?page=equipe&amp;delete=<?= (int)$m['id'] ?>"
           onclick="return confirm('Supprimer ce membre ?')"
           class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 text-sm">Supprimer</a>
      </div>
    </div>
  <?php endforeach; ?>
  <?php if (empty($membres)): ?>
    <p class="text-gray-400">Aucun membre pour le moment.</p>
  <?php endif; ?>
</div>
