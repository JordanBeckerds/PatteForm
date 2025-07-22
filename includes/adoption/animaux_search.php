<?php
    if (! isset($_SESSION['favorites'])) {
        $_SESSION['favorites'] = [];
    }

    // Get filters from GET
    $search         = $_GET['search'] ?? '';
    $espece         = $_GET['espece'] ?? '';
    $raceFilter     = $_GET['race'] ?? '';
    $sexe           = $_GET['sexe'] ?? '';
    $min_age        = $_GET['min_age'] ?? '';
    $max_age        = $_GET['max_age'] ?? '';
    $enfant         = isset($_GET['enfant']) ? 1 : null;
    $chat           = isset($_GET['chat']) ? 1 : null;
    $chien          = isset($_GET['chien']) ? 1 : null;
    $categoriser    = $_GET['categoriser'] ?? '';
    $sos            = isset($_GET['sos']) ? 1 : null;
    $favoriteFilter = isset($_GET['favorite']) ? 1 : null;

    // Fetch unique races from DB for suggestions
    $stmtRaces = $pdo->query("SELECT DISTINCT race FROM animaux_a_adopter WHERE race IS NOT NULL AND race != ''");
    $races     = $stmtRaces->fetchAll(PDO::FETCH_COLUMN);

    // Build SQL
    $sql    = "SELECT * FROM animaux_a_adopter WHERE 1";
    $params = [];

    if ($search !== '') {
        $sql .= " AND (nom LIKE :search OR description LIKE :search)";
        $params[':search'] = '%' . $search . '%';
    }
    if ($espece !== '') {
        $sql .= " AND espece = :espece";
        $params[':espece'] = $espece;
    }
    if ($raceFilter !== '') {
        $sql .= " AND race LIKE :race";
        $params[':race'] = '%' . $raceFilter . '%';
    }
    if ($sexe !== '') {
        $sql .= " AND sexe = :sexe";
        $params[':sexe'] = $sexe;
    }
    if ($min_age !== '') {
        $sql .= " AND age >= :min_age";
        $params[':min_age'] = $min_age;
    }
    if ($max_age !== '') {
        $sql .= " AND age <= :max_age";
        $params[':max_age'] = $max_age;
    }
    if ($enfant === 1) {
        $sql .= " AND enfant = 1";
    }

    if ($chat === 1) {
        $sql .= " AND chat = 1";
    }

    if ($chien === 1) {
        $sql .= " AND chien = 1";
    }

    if ($categoriser !== '') {
        $sql .= " AND categoriser = :categoriser";
        $params[':categoriser'] = $categoriser;
    }
    if ($sos === 1) {
        $sql .= " AND sos = 1";
    }

    // Handle favorite filter: only show favorites if filter active
    if ($favoriteFilter === 1) {
        if (empty($_SESSION['favorites'])) {
            // If no favorites, return no results
            $animaux = [];
        } else {
            $favPlaceholders = [];
            foreach ($_SESSION['favorites'] as $i => $favId) {
                $key               = ":fav_$i";
                $favPlaceholders[] = $key;
                $params[$key]      = $favId;
            }
            $sql .= " AND id IN (" . implode(',', $favPlaceholders) . ")";
        }
    }

    if (! isset($animaux)) {
        // Prepare and execute
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $animaux = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
?>

<div class="flex flex-col mb-24 lg:flex-row p-4 gap-6">
  <!-- FILTER SIDEBAR -->
  <form method="GET" class="w-full min-h-[50vh] lg:w-1/4 bg-gray-50 rounded-xl p-4 shadow-md space-y-4" autocomplete="off">
    <h2 class="text-lg font-semibold">Filtres</h2>

    <input type="text" name="search" placeholder="Rechercher..." value="<?php echo htmlspecialchars($search)?>" class="w-full p-2 border rounded" />

    <select name="espece" class="w-full p-2 border rounded">
      <option value="">Espèce</option>
      <option value="chien" <?php echo $espece === 'chien' ? 'selected' : ''?>>Chien</option>
      <option value="chat" <?php echo $espece === 'chat' ? 'selected' : ''?>>Chat</option>
      <option value="autre" <?php echo $espece === 'autre' ? 'selected' : ''?>>Autre</option>
    </select>

    <!-- Race input with dropdown suggestions -->
    <div class="relative">
      <input type="text" name="race" id="raceInput"
        placeholder="Race (ex: Berger Allemand)"
        value="<?php echo htmlspecialchars($raceFilter)?>"
        class="w-full p-2 border rounded" autocomplete="off" />
      <ul id="raceSuggestions" class="absolute z-10 w-full bg-white border border-gray-300 rounded-b shadow-md hidden max-h-40 overflow-y-auto">
        <!-- Suggestions injected by JS -->
      </ul>
    </div>

    <select name="sexe" class="w-full p-2 border rounded">
      <option value="">Sexe</option>
      <option value="male" <?php echo $sexe === 'male' ? 'selected' : ''?>>Mâle</option>
      <option value="femelle" <?php echo $sexe === 'femelle' ? 'selected' : ''?>>Femelle</option>
    </select>

    <div class="flex gap-2">
      <input type="number" name="min_age" placeholder="Âge min" value="<?php echo htmlspecialchars($min_age)?>" class="w-1/2 p-2 border rounded" />
      <input type="number" name="max_age" placeholder="Âge max" value="<?php echo htmlspecialchars($max_age)?>" class="w-1/2 p-2 border rounded" />
    </div>

    <label class="flex items-center space-x-2">
      <input type="checkbox" name="enfant" <?php echo $enfant === 1 ? 'checked' : ''?> />
      <span>Ami avec enfants</span>
    </label>
    <label class="flex items-center space-x-2">
      <input type="checkbox" name="chat" <?php echo $chat === 1 ? 'checked' : ''?> />
      <span>Ami avec chats</span>
    </label>
    <label class="flex items-center space-x-2">
      <input type="checkbox" name="chien" <?php echo $chien === 1 ? 'checked' : ''?> />
      <span>Ami avec chiens</span>
    </label>

    <select name="categoriser" class="w-full p-2 border rounded">
      <option value="">Catégorisation</option>
      <option value="aucune" <?php echo $categoriser === 'aucune' ? 'selected' : ''?>>Aucune</option>
      <option value="1" <?php echo $categoriser === '1' ? 'selected' : ''?>>Catégorie 1</option>
      <option value="2" <?php echo $categoriser === '2' ? 'selected' : ''?>>Catégorie 2</option>
    </select>

    <label class="flex items-center space-x-2">
      <input type="checkbox" name="sos" <?php echo $sos === 1 ? 'checked' : ''?> />
      <span>SOS uniquement</span>
    </label>

    <label class="flex items-center space-x-2">
      <input type="checkbox" name="favorite" <?php echo $favoriteFilter === 1 ? 'checked' : ''?> />
      <span>Favoris uniquement</span>
    </label>

    <button type="submit" class="bg-[<?php echo $color_tertiary?>] hover:bg-orange-600 text-white w-full p-2 rounded">Rechercher</button>
  </form>

  <!-- RESULTS -->
  <div class="w-full lg:w-3/4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 items-start">
    <?php foreach ($animaux as $animal): ?>
<?php
    $id         = $animal['id'];
    $isFavorite = in_array($id, $_SESSION['favorites']);
    $stmtImg    = $pdo->prepare("SELECT img FROM photo_chiens WHERE id = ?");
    $stmtImg->execute([$id]);
    $img = $stmtImg->fetchColumn() ?: 'https://via.placeholder.com/400x300';
?>
      <a href="adoption.php?id=<?php echo $id?>" class="block bg-white rounded-lg shadow-lg overflow-hidden relative flex flex-col h-[45vh] hover:shadow-[0_0_20px_<?php echo $color_tertiary?>] transition-shadow duration-300">
        <form method="POST" class="absolute top-2 left-2 z-10" onclick="event.stopPropagation();">
          <input type="hidden" name="favorite_id" value="<?php echo $id?>">
          <button type="submit" class="text-4xl <?php echo $isFavorite ? 'text-red-500' : 'text-gray-400'?>" aria-label="Toggle favorite">
            <?php echo $isFavorite ? '❤️' : '🤍'?>
          </button>
        </form>
        <img src="<?php echo htmlspecialchars($img)?>" alt="<?php echo htmlspecialchars($animal['nom'])?>" class="w-full h-[65%] object-cover">
        <div class="p-4 flex flex-col flex-grow h-[35%]">
          <div class="flex gap-2">
            <h3 class="text-xl font-semibold"><?php echo htmlspecialchars($animal['nom'])?></h3>
            <?php
              if ($animal['sexe'] === 'male') {
                  echo '<img src="../assets/img/male.png" alt="Male" width="24" height="12">';
              } elseif ($animal['sexe'] === 'femelle') {
                  echo '<img src="../assets/img/female.png" alt="Femelle" width="24" height="12">';
              } else {
                  echo '<img src="path/to/default.png" alt="Default" width="24" height="24">';
              }
              ?>
          </div>
          <p class="text-black"><?php echo htmlspecialchars($animal['race'])?> - <?php echo htmlspecialchars($animal['age'])?> an(s)</p>
          <p class="text-sm mt-2 overflow-hidden line-clamp-2"><?php echo htmlspecialchars($animal['description'])?></p>
        </div>
      </a>
    <?php endforeach; ?>
  </div>
</div>

<script>
  const raceInput = document.getElementById('raceInput');
  const suggestionsBox = document.getElementById('raceSuggestions');
  const races = <?php echo json_encode($races)?>;

  raceInput.addEventListener('input', () => {
    const value = raceInput.value.toLowerCase();
    suggestionsBox.innerHTML = '';

    if (value === '') {
      suggestionsBox.classList.add('hidden');
      return;
    }

    const matches = races.filter(r => r.toLowerCase().includes(value));
    if (matches.length === 0) {
      suggestionsBox.classList.add('hidden');
      return;
    }

    matches.forEach(race => {
      const li = document.createElement('li');
      li.textContent = race;
      li.className = 'px-4 py-2 hover:bg-blue-100 cursor-pointer';
      li.onclick = () => {
        raceInput.value = race;
        suggestionsBox.classList.add('hidden');
      };
      suggestionsBox.appendChild(li);
    });

    suggestionsBox.classList.remove('hidden');
  });

  // Hide dropdown if clicked outside
  document.addEventListener('click', (e) => {
    if (!raceInput.contains(e.target) && !suggestionsBox.contains(e.target)) {
      suggestionsBox.classList.add('hidden');
    }
  });
</script>

<script>
// Save scroll position before unloading
window.addEventListener('beforeunload', function () {
  localStorage.setItem('scrollY', window.scrollY);
});

// Restore scroll position after loading with smooth scroll
window.addEventListener('load', function () {
  const scrollY = localStorage.getItem('scrollY');
  if (scrollY !== null) {
    window.scrollTo({ top: parseInt(scrollY), behavior: 'smooth' });
    localStorage.removeItem('scrollY');
  }
});
</script>