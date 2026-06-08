<?php
// Included by public/adoption.php (session already started)

if (!isset($_SESSION['favorites'])) {
    $_SESSION['favorites'] = [];
}

$search         = $_GET['search']  ?? '';
$espece         = $_GET['espece']  ?? '';
$raceFilter     = $_GET['race']    ?? '';
$sexe           = $_GET['sexe']    ?? '';
$min_age        = $_GET['min_age'] ?? '';
$max_age        = $_GET['max_age'] ?? '';
$enfant         = isset($_GET['enfant'])   ? 1 : null;
$chat           = isset($_GET['chat'])     ? 1 : null;
$chien          = isset($_GET['chien'])    ? 1 : null;
$categoriser    = $_GET['categoriser']     ?? '';
$sos            = isset($_GET['sos'])      ? 1 : null;
$favoriteFilter = isset($_GET['favorite']) ? 1 : null;

$stmtRaces = $pdo->query('SELECT DISTINCT race FROM animaux_a_adopter WHERE race IS NOT NULL AND race != \'\'');
$races     = $stmtRaces->fetchAll(PDO::FETCH_COLUMN);

$sql    = 'SELECT * FROM animaux_a_adopter WHERE 1';
$params = [];

if ($search !== '')    { $sql .= ' AND (nom LIKE :search OR description LIKE :search)'; $params[':search']     = '%' . $search . '%'; }
if ($espece !== '')    { $sql .= ' AND espece = :espece';                               $params[':espece']     = $espece; }
if ($raceFilter !== '') { $sql .= ' AND race LIKE :race';                              $params[':race']       = '%' . $raceFilter . '%'; }
if ($sexe !== '')      { $sql .= ' AND sexe = :sexe';                                  $params[':sexe']       = $sexe; }
if ($min_age !== '')   { $sql .= ' AND age >= :min_age';                               $params[':min_age']    = $min_age; }
if ($max_age !== '')   { $sql .= ' AND age <= :max_age';                               $params[':max_age']    = $max_age; }
if ($enfant === 1)     $sql .= ' AND enfant = 1';
if ($chat   === 1)     $sql .= ' AND chat = 1';
if ($chien  === 1)     $sql .= ' AND chien = 1';
if ($categoriser !== '') { $sql .= ' AND categoriser = :categoriser'; $params[':categoriser'] = $categoriser; }
if ($sos === 1)        $sql .= ' AND sos = 1';

if ($favoriteFilter === 1) {
    if (empty($_SESSION['favorites'])) {
        $animaux = [];
    } else {
        $favPlaceholders = [];
        foreach ($_SESSION['favorites'] as $i => $favId) {
            $key = ":fav_$i";
            $favPlaceholders[] = $key;
            $params[$key] = $favId;
        }
        $sql .= ' AND id IN (' . implode(',', $favPlaceholders) . ')';
    }
}

if (!isset($animaux)) {
    $stmt    = $pdo->prepare($sql);
    $stmt->execute($params);
    $animaux = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$ct = htmlspecialchars($color_tertiary, ENT_QUOTES, 'UTF-8');
?>

<div class="flex flex-col mb-24 lg:flex-row p-4 gap-6">
  <!-- FILTER SIDEBAR -->
  <form method="GET" class="w-full min-h-[50vh] lg:w-1/4 bg-gray-50 rounded-xl p-4 shadow-md space-y-4" autocomplete="off">
    <h2 class="text-lg font-semibold">Filtres</h2>

    <input type="text" name="search" placeholder="Rechercher..."
           value="<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>"
           class="w-full p-2 border rounded" />

    <select name="espece" class="w-full p-2 border rounded">
      <option value="">Esp&#232;ce</option>
      <option value="chien" <?= $espece === 'chien' ? 'selected' : '' ?>>Chien</option>
      <option value="chat"  <?= $espece === 'chat'  ? 'selected' : '' ?>>Chat</option>
      <option value="autre" <?= $espece === 'autre' ? 'selected' : '' ?>>Autre</option>
    </select>

    <div class="relative">
      <input type="text" name="race" id="raceInput" placeholder="Race (ex: Berger Allemand)"
             value="<?= htmlspecialchars($raceFilter, ENT_QUOTES, 'UTF-8') ?>"
             class="w-full p-2 border rounded" autocomplete="off" />
      <ul id="raceSuggestions"
          class="absolute z-10 w-full bg-white border border-gray-300 rounded-b shadow-md hidden max-h-40 overflow-y-auto"></ul>
    </div>

    <select name="sexe" class="w-full p-2 border rounded">
      <option value="">Sexe</option>
      <option value="male"    <?= $sexe === 'male'    ? 'selected' : '' ?>>M&#226;le</option>
      <option value="femelle" <?= $sexe === 'femelle' ? 'selected' : '' ?>>Femelle</option>
    </select>

    <div class="flex gap-2">
      <input type="number" name="min_age" placeholder="&#194;ge min"
             value="<?= htmlspecialchars($min_age, ENT_QUOTES, 'UTF-8') ?>" class="w-1/2 p-2 border rounded" />
      <input type="number" name="max_age" placeholder="&#194;ge max"
             value="<?= htmlspecialchars($max_age, ENT_QUOTES, 'UTF-8') ?>" class="w-1/2 p-2 border rounded" />
    </div>

    <label class="flex items-center space-x-2"><input type="checkbox" name="enfant" <?= $enfant  === 1 ? 'checked' : '' ?> /><span>Ami avec enfants</span></label>
    <label class="flex items-center space-x-2"><input type="checkbox" name="chat"   <?= $chat    === 1 ? 'checked' : '' ?> /><span>Ami avec chats</span></label>
    <label class="flex items-center space-x-2"><input type="checkbox" name="chien"  <?= $chien   === 1 ? 'checked' : '' ?> /><span>Ami avec chiens</span></label>

    <select name="categoriser" class="w-full p-2 border rounded">
      <option value="">Cat&#233;gorisation</option>
      <option value="aucune" <?= $categoriser === 'aucune' ? 'selected' : '' ?>>Aucune</option>
      <option value="1"      <?= $categoriser === '1'      ? 'selected' : '' ?>>Cat&#233;gorie 1</option>
      <option value="2"      <?= $categoriser === '2'      ? 'selected' : '' ?>>Cat&#233;gorie 2</option>
    </select>

    <label class="flex items-center space-x-2"><input type="checkbox" name="sos"      <?= $sos            === 1 ? 'checked' : '' ?> /><span>SOS uniquement</span></label>
    <label class="flex items-center space-x-2"><input type="checkbox" name="favorite" <?= $favoriteFilter  === 1 ? 'checked' : '' ?> /><span>Favoris uniquement</span></label>

    <button type="submit"
            class="text-white w-full p-2 rounded"
            style="background-color:<?= $ct ?>">
      Rechercher
    </button>
  </form>

  <!-- RESULTS -->
  <div class="w-full lg:w-3/4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 items-start">
    <?php foreach ($animaux as $animal):
        $aid        = $animal['id'];
        $isFavorite = in_array($aid, $_SESSION['favorites'], true);
        $stmtImg    = $pdo->prepare('SELECT img FROM photo_chiens WHERE id = ?');
        $stmtImg->execute([$aid]);
        $img = $stmtImg->fetchColumn() ?: 'https://via.placeholder.com/400x300';
    ?>
      <a href="adoption.php?id=<?= (int)$aid ?>" class="block bg-white rounded-lg shadow-lg overflow-hidden relative flex flex-col h-[45vh] transition-shadow duration-300">
        <form method="POST" class="absolute top-2 left-2 z-10" onclick="event.stopPropagation();">
          <input type="hidden" name="favorite_id" value="<?= (int)$aid ?>">
          <button type="submit"
                  class="text-4xl <?= $isFavorite ? 'text-red-500' : 'text-gray-400' ?>"
                  aria-label="Toggle favori">
            <?= $isFavorite ? '&#10084;&#65039;' : '&#129293;' ?>
          </button>
        </form>
        <img src="<?= htmlspecialchars($img, ENT_QUOTES, 'UTF-8') ?>"
             alt="<?= htmlspecialchars($animal['nom'], ENT_QUOTES, 'UTF-8') ?>"
             class="w-full h-[65%] object-cover">
        <div class="p-4 flex flex-col flex-grow h-[35%]">
          <div class="flex gap-2">
            <h3 class="text-xl font-semibold"><?= htmlspecialchars($animal['nom'], ENT_QUOTES, 'UTF-8') ?></h3>
            <?php if ($animal['sexe'] === 'male'): ?>
              <img src="../assets/img/male.png" alt="M&#226;le" width="24" height="12">
            <?php elseif ($animal['sexe'] === 'femelle'): ?>
              <img src="../assets/img/female.png" alt="Femelle" width="24" height="12">
            <?php endif; ?>
          </div>
          <p class="text-black"><?= htmlspecialchars($animal['race'], ENT_QUOTES, 'UTF-8') ?> &mdash; <?= (int)$animal['age'] ?> an(s)</p>
          <p class="text-sm mt-2 overflow-hidden line-clamp-2"><?= htmlspecialchars($animal['description'], ENT_QUOTES, 'UTF-8') ?></p>
        </div>
      </a>
    <?php endforeach; ?>
  </div>
</div>

<script>
  const raceInput       = document.getElementById('raceInput');
  const suggestionsBox  = document.getElementById('raceSuggestions');
  const races           = <?= json_encode($races, JSON_HEX_TAG | JSON_HEX_AMP) ?>;

  raceInput.addEventListener('input', () => {
    const value = raceInput.value.toLowerCase();
    suggestionsBox.innerHTML = '';
    if (!value) { suggestionsBox.classList.add('hidden'); return; }
    const matches = races.filter(r => r.toLowerCase().includes(value));
    if (!matches.length) { suggestionsBox.classList.add('hidden'); return; }
    matches.forEach(race => {
      const li = document.createElement('li');
      li.textContent = race;
      li.className = 'px-4 py-2 hover:bg-blue-100 cursor-pointer';
      li.onclick = () => { raceInput.value = race; suggestionsBox.classList.add('hidden'); };
      suggestionsBox.appendChild(li);
    });
    suggestionsBox.classList.remove('hidden');
  });
  document.addEventListener('click', e => {
    if (!raceInput.contains(e.target) && !suggestionsBox.contains(e.target))
      suggestionsBox.classList.add('hidden');
  });
</script>
<script>
  window.addEventListener('beforeunload', () => localStorage.setItem('scrollY', window.scrollY));
  window.addEventListener('load', () => {
    const y = localStorage.getItem('scrollY');
    if (y !== null) { window.scrollTo({ top: parseInt(y), behavior: 'smooth' }); localStorage.removeItem('scrollY'); }
  });
</script>
