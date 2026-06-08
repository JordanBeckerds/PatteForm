<?php
// Included by public/index.php — $pdo, $color_* already available from header/config
// Do NOT redefine h() here; use the one from functions.php

$stmtGroup2 = $pdo->prepare('SELECT group_name FROM group_elems WHERE id = 1 LIMIT 1');
$stmtGroup2->execute();
$thumbGroup = $stmtGroup2->fetch(PDO::FETCH_ASSOC);
$groupName  = $thumbGroup['group_name'] ?? 'Patteform';

$stmtActs = $pdo->prepare('SELECT * FROM actualite ORDER BY id DESC LIMIT 3');
$stmtActs->execute();
$actualites = $stmtActs->fetchAll(PDO::FETCH_ASSOC);

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
$host     = $_SERVER['HTTP_HOST'] ?? 'localhost';
$baseUrl  = rtrim($protocol . $host, '/');
?>

<style>
  .actualites-container {
    display:flex; flex-wrap:wrap; justify-content:center; gap:6rem;
  }
  .actualite-card {
    height:50vh; width:20vw; min-width:320px; max-width:100%;
    background-color:<?= htmlspecialchars($color_secondary, ENT_QUOTES, 'UTF-8') ?>;
    border-radius:.5rem; box-shadow:0 1px 3px rgb(0 0 0/.1);
    transition:box-shadow .3s; display:flex; flex-direction:column; overflow:hidden;
  }
  .actualite-card:hover { box-shadow:0 10px 15px rgb(0 0 0/.1); }
  .actualite-image { height:55%; overflow:hidden; }
  .actualite-image img { width:100%; height:100%; object-fit:cover; }
  .actualite-content { padding:1rem; height:45%; display:flex; flex-direction:column; justify-content:space-between; }
  .line-clamp-4 { display:-webkit-box; -webkit-line-clamp:4; -webkit-box-orient:vertical; overflow:hidden; }
  .share-container { position:relative; overflow:hidden; width:126px; transition:width .3s ease-in-out; cursor:pointer; }
  @media (min-width:1025px) {
    .share-container:hover { width:230px; }
    .share-inner { display:flex; align-items:center; transition:transform .3s ease-in-out; }
    .share-container:hover .share-inner { transform:translateX(-54px); }
    .share-text { white-space:nowrap; margin-right:.25rem; color:black; font-size:.875rem; }
    .share-icons { display:flex; align-items:center; gap:.75rem; opacity:0; pointer-events:none; transition:opacity .3s ease-in-out; margin-left:.25rem; }
    .share-container:hover .share-icons { opacity:1; pointer-events:auto; }
  }
  @media (max-width:1024px) {
    .share-container { width:126px !important; overflow:visible !important; }
    .share-inner { display:flex; align-items:center; transform:none !important; transition:none !important; }
    .share-text { white-space:normal; margin-right:.25rem; color:black; font-size:.875rem; }
    .share-icons { display:none !important; }
  }
  @media (max-width:640px) {
    .actualite-card { width:90vw !important; height:auto !important; min-height:300px; margin:0 auto 2rem; }
    .actualite-image { height:200px !important; }
    .actualites-container { gap:1.5rem; }
    .line-clamp-4 { -webkit-line-clamp:2; }
  }
</style>

<div style="background-color:<?= htmlspecialchars($color_primary, ENT_QUOTES, 'UTF-8') ?>" class="py-20">
  <div class="container mx-auto px-4 my-24">
    <div class="actualites-container">
      <?php foreach ($actualites as $act):
          $title      = $act['titre']            ?? 'Titre non disponible';
          $desc       = $act['description']      ?? '';
          $img        = $act['img']              ?? 'https://via.placeholder.com/348x232?text=No+Image';
          $pubDate    = $act['date_publication'] ?? ($act['date'] ?? 'now');
          $local_url  = 'actualites.php?id=' . (int)$act['id'];
          $full_url   = $baseUrl . '/patteform/public/' . $local_url;
          $shareText  = $title . ' — ' . $groupName;
          $mailSubject = $groupName . ' — ' . $title;
      ?>
        <div class="actualite-card">
          <a href="<?= htmlspecialchars($local_url, ENT_QUOTES, 'UTF-8') ?>" class="actualite-image">
            <img src="<?= htmlspecialchars($img, ENT_QUOTES, 'UTF-8') ?>"
                 alt="<?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?>" loading="lazy" />
          </a>
          <div class="actualite-content">
            <div>
              <a href="<?= htmlspecialchars($local_url, ENT_QUOTES, 'UTF-8') ?>">
                <p class="text-sm text-black mb-2"><?= htmlspecialchars(date('d.m.Y', strtotime($pubDate)), ENT_QUOTES, 'UTF-8') ?></p>
                <h3 class="text-md font-semibold text-black"><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></h3>
                <p class="line-clamp-4 text-black"><?= htmlspecialchars($desc, ENT_QUOTES, 'UTF-8') ?></p>
              </a>
            </div>
            <div class="mt-5">
              <div class="share-container"
                   data-share-url="<?= htmlspecialchars($full_url, ENT_QUOTES, 'UTF-8') ?>"
                   data-share-text="<?= htmlspecialchars($shareText, ENT_QUOTES, 'UTF-8') ?>"
                   data-mail-subject="<?= htmlspecialchars($mailSubject, ENT_QUOTES, 'UTF-8') ?>"
                   data-mail-body="<?= htmlspecialchars($full_url, ENT_QUOTES, 'UTF-8') ?>">
                <div class="share-inner">
                  <span class="share-text">Partager</span>
                  <img src="../assets/img/share.png" alt="Partager" class="w-5 h-5 mr-2 flex-shrink-0" />
                  <div class="share-icons">
                    <span>|</span>
                    <a href="https://twitter.com/intent/tweet?text=<?= rawurlencode($shareText) ?>&amp;url=<?= rawurlencode($full_url) ?>"
                       target="_blank" rel="noopener noreferrer" title="Twitter">
                      <img src="../assets/img/twitter.png" alt="Twitter" class="w-5 h-5" />
                    </a>
                    <span>|</span>
                    <a href="mailto:?subject=<?= rawurlencode($mailSubject) ?>&amp;body=<?= rawurlencode($full_url) ?>"
                       title="Email">
                      <img src="../assets/img/email.png" alt="Email" class="w-5 h-5" />
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<script>
  document.querySelectorAll('.share-container').forEach(container => {
    container.addEventListener('click', async function (event) {
      if (window.innerWidth <= 1024 && navigator.share) {
        event.preventDefault();
        try {
          await navigator.share({
            title: container.dataset.shareText,
            text:  container.dataset.shareText,
            url:   container.dataset.shareUrl,
          });
        } catch (e) { /* user cancelled */ }
      }
    });
  });
</script>
