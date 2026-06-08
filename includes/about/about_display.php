<?php
// Included by public/about.php — $pdo, $color_*, $parsedHoraires, etc. from header
?>
<div class="sm:w-[100vw] flex flex-col items-center justify-center mx-auto my-20 sm:my-40 px-4">
  <h1 class="text-4xl sm:text-6xl font-bold mb-12"
      style="color:<?= htmlspecialchars($color_tertiary, ENT_QUOTES, 'UTF-8') ?>">
    &#192; propos de <?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>
  </h1>

  <div class="w-[80vw] sm:w-auto space-y-6 text-xl">

    <div>
      <h2 class="font-semibold">T&#233;l&#233;phone&#160;:</h2>
      <p>+33 <?= htmlspecialchars($telephone, ENT_QUOTES, 'UTF-8') ?></p>
    </div>

    <div class="flex flex-col flex-wrap">
      <h2 class="font-semibold">Horaires d&#39;ouvertures&#160;:</h2>
      <div class="flex gap-10">
        <?php foreach ($parsedHoraires as $periode): ?>
          <div class="flex flex-col">
            <p class="mt-2 ml-2 font-semibold"><?= htmlspecialchars($periode['jours'], ENT_QUOTES, 'UTF-8') ?>&#160;:</p>
            <ul class="list-disc list-inside mb-2">
              <?php foreach ($periode['horaires'] as $horaire): ?>
                <li><?= htmlspecialchars($horaire, ENT_QUOTES, 'UTF-8') ?></li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

    <div class="mt-4">
      <strong>R&#233;seaux sociaux&#160;:</strong>
      <div class="flex flex-col gap-2 ml-2 mt-2">
        <a href="<?= htmlspecialchars($social_facebook, ENT_QUOTES, 'UTF-8') ?>"
           style="color:inherit" class="hover:underline">Facebook</a>
        <a href="<?= htmlspecialchars($social_twitter, ENT_QUOTES, 'UTF-8') ?>"
           style="color:inherit" class="hover:underline">Twitter</a>
        <a href="<?= htmlspecialchars($social_instagram, ENT_QUOTES, 'UTF-8') ?>"
           style="color:inherit" class="hover:underline">Instagram</a>
      </div>
    </div>

    <div class="flex flex-col w-full mx-auto mt-6">
      <h2 class="font-semibold mb-2">Adresse&#160;:</h2>
      <p class="mb-4"><?= htmlspecialchars($adress, ENT_QUOTES, 'UTF-8') ?></p>
      <div class="w-full aspect-video max-w-xl">
        <iframe
          class="w-full h-full rounded"
          loading="lazy"
          referrerpolicy="no-referrer-when-downgrade"
          src="https://www.google.com/maps?q=<?= rawurlencode($adress) ?>&amp;output=embed"
          allowfullscreen>
        </iframe>
      </div>
    </div>

  </div>
</div>
