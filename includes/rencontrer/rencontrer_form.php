<?php

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  echo "<p>Identifiant d'animal invalide.</p>";
  exit;
}

$id = (int)$_GET['id'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $nom = trim(htmlspecialchars($_POST["nom"] ?? ''));
  $prenom = trim(htmlspecialchars($_POST["prenom"] ?? ''));
  $email = trim($_POST["email"] ?? '');
  $telephone = trim($_POST["telephone"] ?? '');
  $date_visite = trim($_POST["date_visite"] ?? '');
  $hh = trim($_POST["heure_hh"] ?? '');
  $mm = trim($_POST["heure_mm"] ?? '');

  $errors = [];

  if (empty($nom)) $errors[] = "Le nom est requis.";
  if (empty($prenom)) $errors[] = "Le prénom est requis.";
  if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email invalide.";
  if (!empty($telephone) && !preg_match('/^\+?[0-9\s\-]{6,20}$/', $telephone)) $errors[] = "Numéro de téléphone invalide.";
  if (empty($date_visite)) $errors[] = "La date est requise.";
  if (!ctype_digit($hh) || (int)$hh < 0 || (int)$hh > 23) $errors[] = "Heure invalide.";
  if (!ctype_digit($mm) || (int)$mm < 0 || (int)$mm > 59) $errors[] = "Minutes invalides.";

  // Validate against past dates
  $today = date('Y-m-d');
  if ($date_visite < $today) $errors[] = "La date de visite ne peut pas être dans le passé.";

  if (empty($errors)) {
    $heure = str_pad($hh, 2, "0", STR_PAD_LEFT) . str_pad($mm, 2, "0", STR_PAD_LEFT); // e.g., 11:30 => "1130"

    try {
      $stmt = $pdo->prepare("INSERT INTO rencontrer (animal_id, nom, prenom, email, telephone, date_de_visite, heure_de_visite) VALUES (?, ?, ?, ?, ?, ?, ?)");
      $stmt->execute([$id, $nom, $prenom, $email, $telephone, $date_visite, $heure]);

      echo "<p class='text-green-600 font-semibold text-center mt-12'>Réservation effectuée avec succès !</p>";
    } catch (PDOException $e) {
      echo "<p class='text-red-600 font-semibold text-center mt-4'>Erreur serveur. Veuillez réessayer plus tard.</p>";
    }
  } else {
    foreach ($errors as $error) {
      echo "<p class='text-red-600 font-semibold text-center mt-2'>{$error}</p>";
    }
  }
}
?>

<body class="min-h-screen">
  <section class="w-full min-h-[80vh] flex items-center justify-center px-4 py-12">
    <form method="POST" action="" class="w-full max-w-2xl bg-white shadow-lg rounded-xl p-8 space-y-6">
      <h2 class="text-3xl font-bold text-center text-gray-800">Réserver une visite</h2>

      <div>
        <label for="prenom" class="block text-gray-700 font-medium">Prénom*</label>
        <input type="text" id="prenom" name="prenom" required class="w-full border border-gray-300 rounded px-4 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-500">
      </div>

      <div>
        <label for="nom" class="block text-gray-700 font-medium">Nom*</label>
        <input type="text" id="nom" name="nom" required class="w-full border border-gray-300 rounded px-4 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-500">
      </div>

      <div>
        <label for="email" class="block text-gray-700 font-medium">Email*</label>
        <input type="email" id="email" name="email" required class="w-full border border-gray-300 rounded px-4 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-500">
      </div>

      <div>
        <label for="telephone" class="block text-gray-700 font-medium">Téléphone</label>
        <input type="tel" id="telephone" name="telephone" class="w-full border border-gray-300 rounded px-4 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-500">
      </div>

      <div>
        <label for="date_visite" class="block text-gray-700 font-medium">Date de visite*</label>
        <input type="date" id="date_visite" name="date_visite" required min="<?= date('Y-m-d') ?>" class="w-full border border-gray-300 rounded px-4 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-500">
      </div>

      <div class="grid grid-cols-2 gap-4">
        <div>
          <label for="heure_hh" class="block text-gray-700 font-medium">Heure (hh)*</label>
          <input type="number" id="heure_hh" name="heure_hh" min="0" max="23" required class="w-full border border-gray-300 rounded px-4 py-2 mt-1">
        </div>
        <div>
          <label for="heure_mm" class="block text-gray-700 font-medium">Minutes (mm)*</label>
          <input type="number" id="heure_mm" name="heure_mm" min="0" max="59" required class="w-full border border-gray-300 rounded px-4 py-2 mt-1">
        </div>
      </div>

      <div class="text-center">
        <button type="submit" class="bg-[<?= $color_tertiary ?>] hover:opacity-70 text-white font-semibold py-2 px-6 rounded transition">
          Réserver
        </button>
      </div>
    </form>
  </section>
</body>