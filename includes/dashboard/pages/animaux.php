<?php
// Animaux management — included by public/dashboard.php

$search        = $_GET['search']  ?? '';
$filter_espece = $_GET['espece']  ?? '';
$sort_order    = strtoupper($_GET['sort'] ?? 'DESC');
if (!in_array($sort_order, ['ASC','DESC'], true)) $sort_order = 'DESC';
$next_sort     = $sort_order === 'ASC' ? 'DESC' : 'ASC';

$edit_data = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare('SELECT * FROM animaux_a_adopter WHERE id = ?');
    $stmt->execute([(int)$_GET['edit']]);
    $edit_data = $stmt->fetch() ?: null;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom         = $_POST['nom']         ?? '';
    $espece      = $_POST['espece']      ?? '';
    $race        = $_POST['race']        ?? '';
    $prix        = $_POST['prix']        ?? 0;
    $sexe        = $_POST['sexe']        ?? '';
    $age         = (int)($_POST['age']   ?? 0);
    $description = $_POST['description'] ?? '';
    $enfant      = isset($_POST['enfant']) ? 1 : 0;
    $chat        = isset($_POST['chat'])   ? 1 : 0;
    $chien       = isset($_POST['chien'])  ? 1 : 0;
    $autre       = isset($_POST['autre'])  ? 1 : 0;
    $categorie   = $_POST['categorie']   ?? 'aucune';
    $sos         = isset($_POST['sos'])    ? 1 : 0;
    $date_arriver = $_POST['date_arriver'] ?? date('Y-m-d');
    $img_urls    = array_filter(array_map('trim', (array)($_POST['img_urls'] ?? [])));

    if (isset($_POST['update_animal'])) {
        $stmt = $pdo->prepare(
            'UPDATE animaux_a_adopter SET nom=?,espece=?,race=?,prix=?,sexe=?,age=?,
             description=?,enfant=?,chat=?,chien=?,autre=?,categorie=?,sos=?,date_arriver=? WHERE id=?'
        );
        $stmt->execute([$nom,$espece,$race,$prix,$sexe,$age,$description,$enfant,$chat,$chien,$autre,$categorie,$sos,$date_arriver,(int)$_POST['id']]);
        $animal_id = (int)$_POST['id'];
        $pdo->prepare('DELETE FROM photo_chiens WHERE id = ?')->execute([$animal_id]);
    } else {
        $stmt = $pdo->prepare(
            'INSERT INTO animaux_a_adopter (nom,espece,race,prix,sexe,age,description,enfant,chat,chien,autre,categorie,sos,date_arriver)
             VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)'
        );
        $stmt->execute([$nom,$espece,$race,$prix,$sexe,$age,$description,$enfant,$chat,$chien,$autre,$categorie,$sos,$date_arriver]);
        $animal_id = (int)$pdo->lastInsertId();
    }

    if ($img_urls) {
        $imgStmt = $pdo->prepare('INSERT INTO photo_chiens (id, img) VALUES (?, ?)');
        foreach ($img_urls as $img) $imgStmt->execute([$animal_id, $img]);
    }
    header('Location: dashboard.php?page=animaux');
    exit;
}

if (isset($_GET['delete'])) {
    $pdo->prepare('DELETE FROM animaux_a_adopter WHERE id = ?')->execute([(int)$_GET['delete']]);
    header('Location: dashboard.php?page=animaux');
    exit;
}

$sql    = 'SELECT * FROM animaux_a_adopter WHERE 1';
$params = [];
if ($search)        { $sql .= ' AND nom LIKE :search';   $params['search'] = "%$search%"; }
if ($filter_espece) { $sql .= ' AND espece = :espece';   $params['espece'] = $filter_espece; }
$sql .= " ORDER BY `date_arriver` $sort_order";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$animaux = $stmt->fetchAll();

$ct = htmlspecialchars($color_tertiary,  ENT_QUOTES, 'UTF-8');
$cs = htmlspecialchars($color_secondary, ENT_QUOTES, 'UTF-8');
$cp = htmlspecialchars($color_primary,   ENT_QUOTES, 'UTF-8');
?>

<h1 class="text-3xl font-bold mb-6" style="color:<?= $ct ?>">Gestion des Animaux</h1>

<form method="POST" class="bg-white p-6 rounded shadow mb-6">
  <input type="hidden" name="id" value="<?= (int)($edit_data['id'] ?? 0) ?>">
  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div><label class="block font-semibold mb-1">Nom</label>
      <input type="text" name="nom" required
             value="<?= htmlspecialchars($edit_data['nom'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
             class="w-full border p-2 rounded"></div>
    <div><label class="block font-semibold mb-1">Esp&#232;ce</label>
      <select name="espece" class="w-full border p-2 rounded" required>
        <option value="">Choisir...</option>
        <option value="chien" <?= ($edit_data['espece'] ?? '') === 'chien' ? 'selected' : '' ?>>Chien</option>
        <option value="chat"  <?= ($edit_data['espece'] ?? '') === 'chat'  ? 'selected' : '' ?>>Chat</option>
        <option value="autre" <?= ($edit_data['espece'] ?? '') === 'autre' ? 'selected' : '' ?>>Autre</option>
      </select></div>
    <div><label class="block font-semibold mb-1">Race</label>
      <input type="text" name="race"
             value="<?= htmlspecialchars($edit_data['race'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
             class="w-full border p-2 rounded"></div>
    <div><label class="block font-semibold mb-1">Prix</label>
      <input type="number" name="prix"
             value="<?= htmlspecialchars($edit_data['prix'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
             class="w-full border p-2 rounded"></div>
    <div><label class="block font-semibold mb-1">Sexe</label>
      <select name="sexe" class="w-full border p-2 rounded">
        <option value="">Choisir...</option>
        <option value="male"    <?= ($edit_data['sexe'] ?? '') === 'male'    ? 'selected' : '' ?>>M&#226;le</option>
        <option value="femelle" <?= ($edit_data['sexe'] ?? '') === 'femelle' ? 'selected' : '' ?>>Femelle</option>
      </select></div>
    <div><label class="block font-semibold mb-1">&#194;ge</label>
      <input type="number" name="age"
             value="<?= (int)($edit_data['age'] ?? 0) ?>"
             class="w-full border p-2 rounded"></div>
    <div class="md:col-span-2"><label class="block font-semibold mb-1">Description</label>
      <textarea name="description" class="w-full border p-2 rounded"
      ><?= htmlspecialchars($edit_data['description'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea></div>
    <div class="md:col-span-2">
      <label class="block font-semibold mb-1">Images</label>
      <div id="img-container" class="space-y-2">
        <?php if ($edit_data):
            $photos = $pdo->prepare('SELECT * FROM photo_chiens WHERE id = ?');
            $photos->execute([$edit_data['id']]);
            foreach ($photos->fetchAll() as $photo):
        ?>
          <input type="text" name="img_urls[]"
                 value="<?= htmlspecialchars($photo['img'], ENT_QUOTES, 'UTF-8') ?>"
                 class="w-full border p-2 rounded">
        <?php endforeach; endif; ?>
        <input type="text" name="img_urls[]" placeholder="Nouvelle image URL" class="w-full border p-2 rounded">
      </div>
      <button type="button" onclick="addImgField()"
              class="mt-2 text-white px-4 py-2 rounded transition"
              style="background-color:<?= $ct ?>">Ajouter une image</button>
    </div>
    <div class="flex flex-wrap gap-4 mt-2">
      <label><input type="checkbox" name="enfant" value="1" <?= ($edit_data['enfant'] ?? 0) ? 'checked' : '' ?> class="mr-2"> Adapt&#233; aux enfants</label>
      <label><input type="checkbox" name="chat"   value="1" <?= ($edit_data['chat']   ?? 0) ? 'checked' : '' ?> class="mr-2"> Ami avec chats</label>
      <label><input type="checkbox" name="chien"  value="1" <?= ($edit_data['chien']  ?? 0) ? 'checked' : '' ?> class="mr-2"> Ami avec chiens</label>
      <label><input type="checkbox" name="autre"  value="1" <?= ($edit_data['autre']  ?? 0) ? 'checked' : '' ?> class="mr-2"> Ami avec autres</label>
      <label><input type="checkbox" name="sos"    value="1" <?= ($edit_data['sos']    ?? 0) ? 'checked' : '' ?> class="mr-2"> SOS</label>
    </div>
    <div><label class="block font-semibold mb-1">Date d&#39;arriv&#233;e</label>
      <input type="date" name="date_arriver"
             value="<?= htmlspecialchars($edit_data['date_arriver'] ?? date('Y-m-d'), ENT_QUOTES, 'UTF-8') ?>"
             class="w-full border p-2 rounded"></div>
  </div>
  <div class="mt-4 flex gap-2">
    <button type="submit" name="<?= isset($edit_data) ? 'update_animal' : 'add_animal' ?>"
            class="text-white px-4 py-2 rounded transition" style="background-color:<?= $ct ?>">
      <?= isset($edit_data) ? 'Mettre &#224; jour' : 'Ajouter' ?>
    </button>
    <?php if (isset($edit_data)): ?>
      <a href="dashboard.php?page=animaux"
         class="bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500 transition">Annuler</a>
    <?php endif; ?>
  </div>
</form>

<script>
function addImgField(){
  const c = document.getElementById('img-container');
  const i = document.createElement('input');
  i.type='text'; i.name='img_urls[]'; i.placeholder='Nouvelle image URL'; i.className='w-full border p-2 rounded';
  c.appendChild(i);
}
</script>

<div class="mb-4 flex flex-wrap gap-4 items-center">
  <form method="GET" class="flex gap-2">
    <input type="hidden" name="page" value="animaux">
    <input type="text" name="search"
           value="<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>"
           placeholder="Rechercher par nom..." class="border p-2 rounded">
    <button type="submit" class="text-white px-4 py-2 rounded transition"
            style="background-color:<?= $ct ?>">Rechercher</button>
  </form>
  <form method="GET" class="flex gap-2">
    <input type="hidden" name="page" value="animaux">
    <select name="espece" onchange="this.form.submit()" class="border p-2 rounded">
      <option value="">Filtrer par esp&#232;ce</option>
      <option value="chien" <?= $filter_espece==='chien'?'selected':'' ?>>Chien</option>
      <option value="chat"  <?= $filter_espece==='chat' ?'selected':'' ?>>Chat</option>
      <option value="autre" <?= $filter_espece==='autre'?'selected':'' ?>>Autre</option>
    </select>
  </form>
  <a href="dashboard.php?page=animaux&amp;search=<?= urlencode($search) ?>&amp;espece=<?= urlencode($filter_espece) ?>&amp;sort=<?= urlencode($next_sort) ?>"
     class="text-white px-4 py-2 rounded transition" style="background-color:<?= $ct ?>">
    Trier par date <?= $sort_order === 'ASC' ? '&#8593;' : '&#8595;' ?>
  </a>
</div>

<table class="w-full border-collapse border border-gray-200 bg-white text-left rounded shadow">
  <thead style="background-color:<?= $cp ?>" class="text-white">
    <tr>
      <th class="p-2 border">Image</th>
      <th class="p-2 border">Nom</th>
      <th class="p-2 border">Esp&#232;ce</th>
      <th class="p-2 border">Race</th>
      <th class="p-2 border">&#194;ge</th>
      <th class="p-2 border">Prix</th>
      <th class="p-2 border">Arriv&#233;e</th>
      <th class="p-2 border">Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($animaux as $dog):
        $dogPhotos = $pdo->prepare('SELECT img FROM photo_chiens WHERE id = ?');
        $dogPhotos->execute([$dog['id']]);
        $dogImgs = $dogPhotos->fetchAll();
    ?>
      <tr class="hover:bg-gray-100">
        <td class="p-2 border flex gap-2">
          <?php if ($dogImgs): foreach ($dogImgs as $p): ?>
            <img src="<?= htmlspecialchars($p['img'], ENT_QUOTES, 'UTF-8') ?>" class="h-16 w-16 object-cover rounded">
          <?php endforeach; else: ?>
            <span class="text-gray-400">Aucune</span>
          <?php endif; ?>
        </td>
        <td class="p-2 border"><?= htmlspecialchars($dog['nom'],        ENT_QUOTES, 'UTF-8') ?></td>
        <td class="p-2 border"><?= htmlspecialchars($dog['espece'],     ENT_QUOTES, 'UTF-8') ?></td>
        <td class="p-2 border"><?= htmlspecialchars($dog['race'],       ENT_QUOTES, 'UTF-8') ?></td>
        <td class="p-2 border"><?= (int)$dog['age'] ?></td>
        <td class="p-2 border"><?= htmlspecialchars($dog['prix'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
        <td class="p-2 border"><?= htmlspecialchars($dog['date_arriver'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
        <td class="p-2 border gap-2">
          <a href="dashboard.php?page=animaux&amp;edit=<?= (int)$dog['id'] ?>"
             class="text-white px-3 py-2 rounded transition" style="background-color:<?= $ct ?>">Modifier</a>
          <a href="dashboard.php?page=animaux&amp;delete=<?= (int)$dog['id'] ?>"
             onclick="return confirm('Supprimer cet animal ?')"
             class="bg-red-500 text-white px-3 py-2 rounded hover:bg-red-600 transition">Supprimer</a>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
