<?php if (!empty($desc_data)): ?>
  <section class="max-w-[60vw] text-black mx-auto pb-48">
    <h2 class="text-4xl font-bold mb-4"><?= htmlspecialchars($desc_data['sec_title']) ?></h2>
    <p class="text-2xl"><?= nl2br(htmlspecialchars($desc_data['sec_txt'])) ?></p>
  </section>
<?php endif; ?>