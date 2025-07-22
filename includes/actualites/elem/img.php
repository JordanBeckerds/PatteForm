<?php if (!empty($img_data['img_url'])): ?>
  <div class="actualite-img pb-48 flex justify-center">
    <img src="<?= htmlspecialchars($img_data['img_url']) ?>" alt="Image actualité" class="max-w-[80%] pt-20 h-auto rounded-lg ">
  </div>
<?php endif; ?>