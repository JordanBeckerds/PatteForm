<?php
// Included by public/contact.php

$contact_errors  = [];
$contact_success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect raw — h() / htmlspecialchars used only on output, not on storage
    $prenom    = trim($_POST['prenom']    ?? '');
    $nom       = trim($_POST['nom']       ?? '');
    $email     = trim($_POST['email']     ?? '');
    $telephone = trim($_POST['telephone'] ?? '');
    $sujet     = trim($_POST['sujet']     ?? '');
    $message   = trim($_POST['message']   ?? '');

    if (empty($nom))                                           $contact_errors[] = 'Le nom est obligatoire.';
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $contact_errors[] = 'Veuillez entrer un email valide.';
    if (empty($message))                                       $contact_errors[] = 'Le message est obligatoire.';
    if (!empty($telephone) && !preg_match('/^\+?[0-9\s\-]{6,20}$/', $telephone))
        $contact_errors[] = 'Le num&#233;ro de t&#233;l&#233;phone est invalide.';

    if (empty($contact_errors)) {
        try {
            $stmt = $pdo->prepare(
                'INSERT INTO contact (prenom, nom, email, telephone, sujet, message) VALUES (?, ?, ?, ?, ?, ?)'
            );
            $stmt->execute([$prenom, $nom, $email, $telephone, $sujet, $message]);
            $contact_success = true;
        } catch (PDOException $e) {
            error_log('PatteForm contact: ' . $e->getMessage());
            $contact_errors[] = 'Erreur serveur. Veuillez r&#233;essayer plus tard.';
        }
    }
}
?>

<section class="w-full min-h-[80vh] flex items-center justify-center px-4 py-12">
  <form method="POST" action="" class="w-full max-w-2xl bg-white shadow-lg rounded-xl p-8 space-y-6">
    <h2 class="text-3xl font-bold text-center text-gray-800">Nous Contacter</h2>

    <?php if ($contact_success): ?>
      <p class="text-green-600 font-semibold text-center">Merci pour votre message&#160;! Nous vous r&#233;pondrons bient&#244;t.</p>
    <?php endif; ?>

    <?php foreach ($contact_errors as $err): ?>
      <p class="text-red-600 font-semibold text-center"><?= htmlspecialchars($err, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endforeach; ?>

    <div>
      <label for="prenom" class="block text-gray-700 font-medium">Pr&#233;nom</label>
      <input type="text" id="prenom" name="prenom"
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
      <label for="sujet" class="block text-gray-700 font-medium">Sujet</label>
      <input type="text" id="sujet" name="sujet"
             value="<?= htmlspecialchars($_POST['sujet'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
             class="w-full border border-gray-300 rounded px-4 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-500">
    </div>
    <div>
      <label for="message" class="block text-gray-700 font-medium">Message*</label>
      <textarea id="message" name="message" rows="6" required
                class="w-full border border-gray-300 rounded px-4 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-500"
      ><?= htmlspecialchars($_POST['message'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
    </div>
    <div class="text-center">
      <button type="submit"
              class="hover:opacity-70 text-white font-semibold py-2 px-6 rounded transition"
              style="background-color:<?= htmlspecialchars($color_tertiary, ENT_QUOTES, 'UTF-8') ?>">
        Envoyer
      </button>
    </div>
  </form>
</section>
