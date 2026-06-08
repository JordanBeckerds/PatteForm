<?php
// Included by public/rencontrer.php

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<p class='text-center text-red-600 py-8'>Identifiant d'animal invalide.</p>";
    return;
}

$id = (int)$_GET['id'];
$rencontrer_errors  = [];
$rencontrer_success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom         = trim($_POST['nom']         ?? '');
    $prenom      = trim($_POST['prenom']      ?? '');
    $email       = trim($_POST['email']       ?? '');
    $telephone   = trim($_POST['telephone']   ?? '');
    $date_visite = trim($_POST['date_visite'] ?? '');
    $hh          = trim($_POST['heure_hh']    ?? '');
    $mm          = trim($_POST['heure_mm']    ?? '');

    if (empty($nom))                                                  $rencontrer_errors[] = 'Le nom est requis.';
    if (empty($prenom))                                               $rencontrer_errors[] = 'Le pr&#233;nom est requis.';
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL))  $rencontrer_errors[] = 'Email invalide.';
    if (!empty($telephone) && !preg_match('/^\+?[0-9\s\-]{6,20}$/', $telephone))
        $rencontrer_errors[] = 'Num&#233;ro de t&#233;l&#233;phone invalide.';
    if (empty($date_visite))                                          $rencontrer_errors[] = 'La date est requise.';
    if (!ctype_digit($hh) || (int)$hh < 0 || (int)$hh > 23)         $rencontrer_errors[] = 'Heure invalide.';
    if (!ctype_digit($mm) || (int)$mm < 0 || (int)$mm > 59)         $rencontrer_errors[] = 'Minutes invalides.';
    if ($date_visite < date('Y-m-d'))                                 $rencontrer_errors[] = 'La date ne peut pas &#234;tre dans le pass&#233;.';

    if (empty($rencontrer_errors)) {
        $heure = str_pad($hh, 2, '0', STR_PAD_LEFT) . str_pad($mm, 2, '0', STR_PAD_LEFT);
        try {
            $stmt = $pdo->prepare(
                'INSERT INTO rencontrer (animal_id, nom, prenom, email, telephone, date_de_visite, heure_de_visite)
                 VALUES (?, ?, ?, ?, ?, ?, ?)'
            );
            $stmt->execute([$id, $nom, $prenom, $email, $telephone, $date_visite, $heure]);
            $rencontrer_success = true;
        } catch (PDOException $e) {
            error_log('PatteForm rencontrer: ' . $e->getMessage());
            $rencontrer_errors[] = 'Erreur serveur. Veuillez r&#233;essayer plus tard.';
        }
    }
}
?>

<section class="w-full min-h-[80vh] flex items-center justify-center px-4 py-12">
  <form method="POST" action="" class="w-full max-w-2xl bg-white shadow-lg rounded-xl p-8 space-y-6">
    <h2 class="text-3xl font-bold text-center text-gray-800">R&#233;server une visite</h2>

    <?php if ($rencontrer_success): ?>
      <p class="text-green-600 font-semibold text-center">R&#233;servation effectu&#233;e avec succ&#232;s&#160;!</p>
    <?php endif; ?>
    <?php foreach ($rencontrer_errors as $err): ?>
      <p class="text-red-600 font-semibold text-center"><?= htmlspecialchars($err, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endforeach; ?>

    <div>
      <label for="prenom" class="block text-gray-700 font-medium">Pr&#233;nom*</label>
      <input type="text" id="prenom" name="prenom" required
             value="<?= htmlspecialchars($_POST['prenom'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
             class="w-full border border-gray-300 rounded px-4 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-500">
    </div>
    <div>
      <label for="nom" class="block text-gray-700 font-medium">Nom*</label>
      <input type="text" id="nom" name="nom" required
             value="<?= htmlspecialchars($_POST['nom'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
             class="w-full border border-gray-300 rounded px-4 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-500">
    </div>
    <div>
      <label for="email" class="block text-gray-700 font-medium">Email*</label>
      <input type="email" id="email" name="email" required
             value="<?= htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
             class="w-full border border-gray-300 rounded px-4 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-500">
    </div>
    <div>
      <label for="telephone" class="block text-gray-700 font-medium">T&#233;l&#233;phone</label>
      <input type="tel" id="telephone" name="telephone"
             value="<?= htmlspecialchars($_POST['telephone'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
             class="w-full border border-gray-300 rounded px-4 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-500">
    </div>
    <div>
      <label for="date_visite" class="block text-gray-700 font-medium">Date de visite*</label>
      <input type="date" id="date_visite" name="date_visite" required
             min="<?= date('Y-m-d') ?>"
             value="<?= htmlspecialchars($_POST['date_visite'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
             class="w-full border border-gray-300 rounded px-4 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-500">
    </div>
    <div class="grid grid-cols-2 gap-4">
      <div>
        <label for="heure_hh" class="block text-gray-700 font-medium">Heure (hh)*</label>
        <input type="number" id="heure_hh" name="heure_hh" min="0" max="23" required
               value="<?= htmlspecialchars($_POST['heure_hh'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
               class="w-full border border-gray-300 rounded px-4 py-2 mt-1">
      </div>
      <div>
        <label for="heure_mm" class="block text-gray-700 font-medium">Minutes (mm)*</label>
        <input type="number" id="heure_mm" name="heure_mm" min="0" max="59" required
               value="<?= htmlspecialchars($_POST['heure_mm'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
               class="w-full border border-gray-300 rounded px-4 py-2 mt-1">
      </div>
    </div>
    <div class="text-center">
      <button type="submit"
              class="hover:opacity-70 text-white font-semibold py-2 px-6 rounded transition"
              style="background-color:<?= htmlspecialchars($color_tertiary, ENT_QUOTES, 'UTF-8') ?>">
        R&#233;server
      </button>
    </div>
  </form>
</section>
