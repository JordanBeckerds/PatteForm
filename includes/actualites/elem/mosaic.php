<?php if (!empty($mosaic_images)): ?>
  <section class="max-w-[80vw] mx-auto pb-48 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
    <?php foreach ($mosaic_images as $img): ?>
      <img src="<?= htmlspecialchars($img['img_url']) ?>" alt="Image mosaïque" class="rounded-xl object-cover w-full h-[40vh] shadow-md" />
    <?php endforeach; ?>
  </section>
<?php endif; ?>