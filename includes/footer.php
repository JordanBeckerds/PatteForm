<footer class="bg-[<?= htmlspecialchars($color_primary) ?>] shadow-inner">
  <div class="container mx-auto px-4 py-8 text-center text-black text-sm">
    <p>&copy; <?= date('Y'); ?> Patteform. Tous droits réservés.</p>

    <!-- Address line -->
    <p class="mt-2">
      <a href="https://www.google.com/maps/search/?api=1&query=<?= urlencode($adress) ?>" 
        target="_blank" 
        rel="noopener noreferrer" 
        class="mx-2 hover:text-[<?= htmlspecialchars($color_tertiary) ?>]">
        <?= htmlspecialchars($adress) ?>
      </a>
    </p>

    <div class="flex align-center justify-center gap-[2vw]">
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

    <p class="mt-2">
      <a href="tel:+33<?= htmlspecialchars($telephone) ?>" class="mx-2 hover:text-[<?= htmlspecialchars($color_tertiary) ?>]">Tel: +33 <?= htmlspecialchars($telephone) ?></a>
    </p>

    <!-- Social media links -->
    <p class="mt-2">
      <a href="<?= htmlspecialchars($social_facebook, ENT_QUOTES, 'UTF-8') ?>" class="mx-2 hover:text-[<?= htmlspecialchars($color_tertiary) ?>]">Facebook</a>|
      <a href="<?= htmlspecialchars($social_twitter, ENT_QUOTES, 'UTF-8') ?>" class="mx-2 hover:text-[<?= htmlspecialchars($color_tertiary) ?>]">Twitter</a>|
      <a href="<?= htmlspecialchars($social_instagram, ENT_QUOTES, 'UTF-8') ?>" class="mx-2 hover:text-[<?= htmlspecialchars($color_tertiary) ?>]">Instagram</a>
    </p>
  </div>
</footer>
</body>
</html>