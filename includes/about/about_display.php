  <div class="sm:w-[100vw] flex flex-col items-center justify-center mx-auto my-20 sm:my-40 px-4">
    <h1 class="text-4xl sm:text-6xl font-bold text-[<?= $color_tertiary ?>] mb-12">À propos de <?= htmlspecialchars($name) ?></h1>
    <div class="w-[80vw] sm:w-auto space-y-6 text-6l sm:text-xl">
      <div>
        <h2 class="font-semibold">Téléphone :</h2>  
        <p>+33 <?= htmlspecialchars($telephone) ?></p>
      </div>
      <div class="flex flex-col align-center flex-wrap">
        <h2 class="font-semibold">Horaires d'ouvertures :</h2>
        <div class="flex gap-10">
          <?php foreach ($parsedHoraires as $periode): ?>
            <div class="flex flex-col align-center">
              <p class="mt-2 ml-2 font-semibold"><?= htmlspecialchars($periode['jours']) ?> :</p>
              <ul class="list-disc list-inside mb-2">
                <?php foreach ($periode['horaires'] as $horaire): ?>
                  <li><?= htmlspecialchars($horaire) ?></li>
                <?php endforeach; ?>
              </ul>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
      
      <div class="mt-4">
        <strong>Réseaux sociaux :</strong>
        <div class="flex flex-col gap-2 ml-2 mt-2">
          <a href="<?= htmlspecialchars($social_facebook) ?>" class="hover:text-[<?= $color_tertiary ?>]">Facebook</a>
          <a href="<?= htmlspecialchars($social_twitter) ?>" class="hover:text-[<?= $color_tertiary ?>]">Twitter</a>
          <a href="<?= htmlspecialchars($social_instagram) ?>" class="hover:text-[<?= $color_tertiary ?>]">Instagram</a>
        </div>
      </div>

      <div class="flex flex-col w-full mx-auto mt-6">
        <h2 class="font-semibold mb-2">Adresse :</h2>  
        <p class="mb-4"><?= htmlspecialchars($adress) ?></p>
        <div class="w-full aspect-video max-w-xl">
          <iframe
            class="w-full h-full rounded"
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"
            src="https://www.google.com/maps?q=<?= urlencode($adress) ?>&output=embed"
            allowfullscreen>
          </iframe>
        </div>
      </div>
    </div>
  </div>
