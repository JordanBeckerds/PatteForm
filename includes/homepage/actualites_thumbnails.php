<?php

$stmt = $pdo->prepare("SELECT * FROM actualite ORDER BY id DESC LIMIT 3");
$stmt->execute();
$actualites = $stmt->fetchAll(PDO::FETCH_ASSOC);

function h($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

$baseUrl = 'https://www.la-spa.fr';
?>

<div class="bg-white py-20">
  <div class="container mx-auto px-4">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 flex-wrap">

      <?php foreach ($actualites as $index => $act): 
          $title = $act['titre'] ?? 'Titre non disponible';
          $description = $act['description'] ?? 'Descritpion non disponible';
          $slug = $act['slug'] ?? '';
          $url = '../public/actualites.php?id=' . h($act['id']);
          $date = date('d.m.Y', strtotime($act['date'] ?? 'now'));
          $img = $act['img'] ?? 'https://via.placeholder.com/348x232?text=No+Image';
          $shareUrl = $baseUrl . $url;
          $shareText = $title . ' - LA SPA';
          $mailSubject = 'SPA-' . $title . ' - LA SPA';
          $mailBody = $shareUrl;
      ?>
        <div class="bg-gray-50 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 overflow-hidden flex flex-col h-[50vh] w-[20vw] mx-auto">
          <a href="<?= h($url) ?>" class="block w-full h-[55%] overflow-hidden">
            <img src="<?= h($img) ?>" alt="<?= h($title) ?>" class="w-full h-full object-cover" loading="lazy" />
          </a>

          <div class="p-4 h-[45%] flex flex-col justify-between flex-grow text-base">
            <div>
              <a href="<?= h($url) ?>">
                <p class="text-sm text-gray-400 mb-2"><?= h($date) ?></p>
                <h3 class="text-md font-semibold text-gray-900 hover:text-orange-600 transition-colors"><?= h($title) ?></h3>
                <p class="line-clamp-4" ><?= h($description) ?></p>
              </a>
            </div>

            <div class="mt-5">
              <div 
                class="relative group overflow-hidden w-[126px] hover:w-[230px] transition-all duration-300 ease-in-out cursor-pointer"
                data-share-url="<?= h($shareUrl) ?>"
                data-share-text="<?= h($shareText) ?>"
                data-mail-subject="<?= h($mailSubject) ?>"
                data-mail-body="<?= h($mailBody) ?>"
              >
                <div class="flex items-center transition-transform duration-300 ease-in-out group-hover:-translate-x-[54px]">

                  <!-- Texte "Partager" qui slide -->
                  <span class="text-sm text-gray-700 whitespace-nowrap min-w-max mr-1">Partager</span>

                  <!-- Share Icon (reste visible) -->
                  <img src="../assets/img/share.png" alt="Partager" class="w-5 h-5 mr-2 flex-shrink-0" />

                  <!-- Contenu masqué par défaut -->
                  <div class="flex items-center space-x-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300 ml-1 pointer-events-none group-hover:pointer-events-auto">
                    <span class="text-gray-400 select-none">|</span>
                    <a href="https://twitter.com/intent/tweet?text=<?= rawurlencode($shareText) ?>&url=<?= rawurlencode($shareUrl) ?>" target="_blank" rel="noopener noreferrer">
                      <img src="../assets/img/twitter.png" alt="Twitter" class="w-5 h-5" />
                    </a>
                    <a href="https://www.instagram.com/" target="_blank" rel="noopener noreferrer">
                      <img src="../assets/img/instagram.png" alt="Instagram" class="w-5 h-5" />
                    </a>
                    <a href="mailto:?subject=<?= rawurlencode($mailSubject) ?>&body=<?= rawurlencode($mailBody) ?>" target="_blank" rel="noopener noreferrer">
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

    <div class="mt-24 text-center">
      <a href="../public/actualites.php" class="inline-block bg-orange-600 text-white py-3 px-8 rounded-md font-medium hover:bg-orange-700 transition-colors">
        Voir toutes les actualités
      </a>
    </div>
  </div>
</div>

<script>
  // Detect if Web Share API is supported (for mobile)
  function isMobileShareSupported() {
    return navigator.share !== undefined;
  }

  // Attach click handlers for mobile share buttons
  document.querySelectorAll('.group').forEach(btn => {
    btn.addEventListener('click', e => {
      if (window.innerWidth <= 768 && isMobileShareSupported()) {  // Mobile breakpoint
        e.preventDefault();
        const shareUrl = btn.getAttribute('data-share-url');
        const shareText = btn.getAttribute('data-share-text');

        navigator.share({
          title: shareText,
          text: shareText,
          url: shareUrl
        }).catch((error) => {
          console.error('Error sharing:', error);
        });
      }
    });
  });
</script>