<?php
require_once '../includes/session.php';
require_once '../includes/config.php';
require_once '../includes/functions.php';
?>
<?php include '../includes/header.php'; ?>

<section class="min-h-[70vh] flex flex-col items-center justify-center px-4 py-16">
  <div class="max-w-xl w-full bg-white rounded-2xl shadow-lg p-10 text-center">
    <h1 class="text-4xl font-bold mb-4" style="color:<?= htmlspecialchars($color_tertiary, ENT_QUOTES, 'UTF-8') ?>">
      Faire un don
    </h1>
    <p class="text-gray-600 mb-8 text-lg">
      Votre générosité permet à nos animaux de trouver un foyer aimant.
      Chaque don, même petit, fait une grande différence.
    </p>
    <p class="text-gray-500">
      Pour faire un don, veuillez nous contacter directement ou utiliser le lien fourni par notre refuge.
    </p>
    <a href="contact.php"
       class="mt-8 inline-block px-8 py-3 rounded-full text-white font-semibold transition hover:opacity-80"
       style="background-color:<?= htmlspecialchars($color_tertiary, ENT_QUOTES, 'UTF-8') ?>">
      Nous contacter
    </a>
  </div>
</section>

<?php include '../includes/donate_btn.php'; ?>
<?php include '../includes/footer.php'; ?>
