<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Sanitize inputs
  $prenom = trim(htmlspecialchars($_POST["prenom"] ?? ''));
  $nom = trim(htmlspecialchars($_POST["nom"] ?? ''));
  $email = trim($_POST["email"] ?? '');
  $telephone = trim($_POST["telephone"] ?? '');
  $sujet = trim(htmlspecialchars($_POST["sujet"] ?? ''));
  $message = trim(htmlspecialchars($_POST["message"] ?? ''));

  // Validate required fields
  $errors = [];

  if (empty($nom)) {
    $errors[] = "Le nom est obligatoire.";
  }

  if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Veuillez entrer un email valide.";
  }

  if (empty($message)) {
    $errors[] = "Le message est obligatoire.";
  }

  // Optional: validate phone number format (simple regex)
  if (!empty($telephone) && !preg_match('/^\+?[0-9\s\-]{6,20}$/', $telephone)) {
    $errors[] = "Le numéro de téléphone est invalide.";
  }

  if (empty($errors)) {
    try {

      $stmt = $pdo->prepare("INSERT INTO contact (prenom, nom, email, telephone, sujet, message) VALUES (?, ?, ?, ?, ?, ?)");
      $stmt->execute([$prenom, $nom, $email, $telephone, $sujet, $message]);

      echo "<p class='text-green-600 font-semibold text-center mt-12'>Merci pour votre message ! Nous vous répondrons bientôt.</p>";
    } catch (PDOException $e) {
      // Log the error $e->getMessage() somewhere secure (not displayed to user)
      echo "<p class='text-red-600 font-semibold text-center mt-4'>Erreur serveur. Veuillez réessayer plus tard.</p>";
    }
  } else {
    // Display errors
    foreach ($errors as $error) {
      echo "<p class='text-red-600 font-semibold text-center mt-2'>{$error}</p>";
    }
  }
}
?>

<body class=" min-h-screen">
  <section class="w-full min-h-[80vh] flex items-center justify-center px-4 py-12">
    <form method="POST" action="" class="w-full max-w-2xl bg-white shadow-lg rounded-xl p-8 space-y-6">
      <h2 class="text-3xl font-bold text-center text-gray-800">Nous Contacter</h2>

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
        <label for="sujet" class="block text-gray-700 font-medium">Sujet</label>
        <input type="text" id="sujet" name="sujet" class="w-full border border-gray-300 rounded px-4 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-500">
      </div>

      <div>
        <label for="message" class="block text-gray-700 font-medium">Message*</label>
        <textarea id="message" name="message" rows="6" required class="w-full border border-gray-300 rounded px-4 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
      </div>

      <div class="text-center">
        <button type="submit" class="bg-[<?= $color_tertiary ?>] hover:opacity-70 text-white font-semibold py-2 px-6 rounded transition">
          Envoyer
        </button>
      </div>
    </form>
  </section>
</body>