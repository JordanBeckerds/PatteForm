<?php if (!empty($vid_data['vid_url'])): ?>
  <div class="video-section pb-48 my-4">
    <iframe width="560" height="315" src="<?= htmlspecialchars($vid_data['vid_url']) ?>" frameborder="0" allowfullscreen></iframe>
  </div>
<?php else: ?>
  <p>Vidéo indisponible.</p>
<?php endif; ?>