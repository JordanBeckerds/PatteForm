<?php
// Fetch the single group_name from group_elems where id=1
$stmtGroup = $pdo->prepare("SELECT group_name FROM group_elems WHERE id = 1 LIMIT 1");
$stmtGroup->execute();
$group = $stmtGroup->fetch(PDO::FETCH_ASSOC);
$groupName = $group['group_name'] ?? 'LA SPA';

// Fetch latest 3 actualites
$stmt = $pdo->prepare("SELECT * FROM actualite ORDER BY id DESC LIMIT 3");
$stmt->execute();
$actualites = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Escape helper
function h($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'];
$baseUrl = rtrim($protocol . $host, '/');

// Fetch group_elem data from DB (assuming $pdo is your PDO connection)
$stmt = $pdo->query("SELECT * FROM group_elems LIMIT 1");
$group = $stmt->fetch(PDO::FETCH_ASSOC);

$logo = $group['logo'];

$color_primary = $group['color_primary'] ?? '#FFFFFF';      // For backgrounds except text
$color_secondary = $group['color_secondary'] ?? '#FEF4EE';   // For secondary backgrounds except text
$color_tertiary = $group['color_tertiary'] ?? '#F97316';     // For highlights except text
?>

<style>
  /* Container for actualites */
  .actualites-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 6rem; /* default gap between thumbnails */
  }

  /* Default actualite-card style */
  .actualite-card {
    height: 50vh;
    width: 20vw;
    min-width: 320px;
    max-width: 100%;
    background-color: <?= $color_secondary ?>; /* bg-gray-50 */
    border-radius: 0.5rem; /* rounded-lg */
    box-shadow: 0 1px 3px rgb(0 0 0 / 0.1); /* shadow-md */
    transition: box-shadow 0.3s ease;
    display: flex;
    flex-direction: column;
    overflow: hidden;
  }

  .actualite-card:hover {
    box-shadow: 0 10px 15px rgb(0 0 0 / 0.1); /* shadow-lg */
  }

  /* Image section */
  .actualite-image {
    height: 55%;
    overflow: hidden;
  }

  .actualite-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }

  /* Content */
  .actualite-content {
    padding: 1rem;
    height: 45%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    font-size: 1rem; /* text-base */
  }

  /* Clamp description text - default desktop 4 lines */
  .line-clamp-4 {
    display: -webkit-box;
    -webkit-line-clamp: 4;
    -webkit-box-orient: vertical;
    overflow: hidden;
  }

  /* Share container styles */
  .share-container {
    position: relative;
    overflow: hidden;
    width: 126px;
    transition: width 0.3s ease-in-out;
    cursor: pointer;
  }

  /* Desktop hover animation only for desktop */
  @media (min-width: 1025px) {
    .share-container:hover {
      width: 230px;
    }

    .share-inner {
      display: flex;
      align-items: center;
      transition: transform 0.3s ease-in-out;
    }

    .share-container:hover .share-inner {
      transform: translateX(-54px);
    }

    .share-text {
      white-space: nowrap;
      margin-right: 0.25rem;
      color: black; /* text-gray-700 */
      font-size: 0.875rem; /* text-sm */
    }

    .share-icons {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      opacity: 0;
      pointer-events: none;
      transition: opacity 0.3s ease-in-out;
      margin-left: 0.25rem;
    }

    .share-container:hover .share-icons {
      opacity: 1;
      pointer-events: auto;
    }

    .share-icons span.separator {
      color: black; /* text-black */
      user-select: none;
    }
  }

  /* On mobile and iPad: no hover animation, share button fully visible */
  @media (max-width: 1024px) {
    .share-container {
      width: 126px !important;
      overflow: visible !important;
    }

    .share-inner {
      display: flex;
      align-items: center;
      transform: none !important;
      transition: none !important;
    }

    .share-text {
      white-space: normal;
      margin-right: 0.25rem;
      color: black; /* text-gray-700 */
      font-size: 0.875rem; /* text-sm */
    }

    .share-icons {
      display: none !important;
      opacity: 0 !important;
      pointer-events: none !important;
      margin-left: 0;
      gap: 0;
      transition: none !important;
    }
  }

  /* Responsive fixes */

  /* Mobile first: single column stacked */
  @media (max-width: 640px) {
    .actualite-card {
      width: 90vw !important;
      height: auto !important;
      min-height: 300px;
      margin: 0 auto 2rem;
      flex-direction: column;
    }

    .actualite-image {
      height: 200px !important;
    }

    .actualites-container {
      gap: 1.5rem;
    }

    /* Clamp text at 2 lines on mobile */
    .line-clamp-4 {
      -webkit-line-clamp: 2;
    }
  }

  /* iPad (641px to 1024px): clamp description at 1 line */
  @media (min-width: 641px) and (max-width: 1024px) {
    .line-clamp-4 {
      -webkit-line-clamp: 1;
    }
  }

  /* Medium screens: wider, with more gap between thumbnails */
  @media (min-width: 641px) and (aspect-ratio: 16/9) {
    .actualites-container {
      gap: 3rem; /* bigger gap on 16:9 aspect ratio */
      justify-content: center;
    }
  }

  /* Smaller 16:9 screens (but above mobile width), grid 2 top + 1 bottom */
  /* We'll define max-width for "smaller 16:9" — let's say max-width: 900px */
  @media (max-width: 900px) and (min-width: 641px) and (aspect-ratio: 16/9) {
    .actualites-container {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      grid-template-rows: auto auto;
      gap: 2rem;
      justify-items: center;
      max-width: 700px;
      margin: 0 auto;
    }

    .actualite-card {
      width: 320px; /* fixed width for better grid control */
      height: auto;
      min-height: 350px;
    }

    /* The third card should go on the second row, spanning both columns or centered */
    .actualites-container > div:nth-child(3) {
      grid-column: 1 / span 2;
      justify-self: center;
      width: 320px;
    }
  }
</style>

<div class="bg-[<?= $color_primary ?>] py-20">
  <div class="container mx-auto px-4 my-24">

    <div class="actualites-container">
      <?php foreach ($actualites as $index => $act): 
          $title = $act['titre'] ?? 'Titre non disponible';
          $description = $act['description'] ?? 'Description non disponible';
          $slug = $act['slug'] ?? '';
          $local_url = '/actualites.php?id=' . h($act['id']);
          $url = '/patteform/public/actualites.php?id=' . h($act['id']);
          $date = date('d.m.Y', strtotime($act['date'] ?? 'now'));
          $img = $act['img'] ?? 'https://via.placeholder.com/348x232?text=No+Image';

          // Use the fetched groupName for all items
          $shareUrl = $baseUrl . $url;
          $shareText = $title . ' - ' . $groupName;
          $mailSubject = $groupName . ' - ' . $title;
          $mailBody = $shareUrl;
      ?>
        <div class="actualite-card">
          <a href="<?= h($local_url) ?>" class="actualite-image">
            <img src="<?= h($img) ?>" alt="<?= h($title) ?>" loading="lazy" />
          </a>

          <div class="actualite-content">
            <div>
              <a href="<?= h($local_url) ?>">
                <p class="text-sm text-black mb-2"><?= h($date) ?></p>
                <h3 class="text-md font-semibold text-black transition-colors"><?= h($title) ?></h3>
                <p class="line-clamp-4 text-black"><?= h($description) ?></p>
              </a>
            </div>

            <div class="mt-5">
              <div 
                class="share-container"
                data-share-url="<?= h($shareUrl) ?>"
                data-share-text="<?= h($shareText) ?>"
                data-mail-subject="<?= h($mailSubject) ?>"
                data-mail-body="<?= h($mailBody) ?>"
              >
                <div class="share-inner">

                  <!-- Texte "Partager" qui slide only on desktop -->
                  <span class="share-text">Partager</span>

                  <!-- Share Icon (always visible) -->
                  <img src="../assets/img/share.png" alt="Partager" class="w-5 h-5 mr-2 flex-shrink-0" />

                  <!-- Contenu masqué par défaut (desktop only) -->
                  <div class="share-icons">
                    <span class="separator">|</span>
                    <a href="https://twitter.com/intent/tweet?text=<?= rawurlencode($shareText) ?>&url=<?= rawurlencode($shareUrl) ?>" target="_blank" rel="noopener noreferrer" title="Partager sur Twitter">
                      <img src="../assets/img/twitter.png" alt="Twitter" class="w-5 h-5" />
                    </a>
                    <span class="separator">|</span>
                    <a href="mailto:?subject=<?= rawurlencode($mailSubject) ?>&body=<?= rawurlencode($mailBody) ?>" target="_blank" title="Partager par Email">
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
  // Share container click event for native share API on mobile and iPad (<= 1024px width)
  document.querySelectorAll('.share-container').forEach(container => {
    container.addEventListener('click', async function(event) {
      const screenWidth = window.innerWidth;
      const shareUrl = container.getAttribute('data-share-url');
      const shareText = container.getAttribute('data-share-text');
      const mailSubject = container.getAttribute('data-mail-subject');
      const mailBody = container.getAttribute('data-mail-body');

      // Only trigger native share API on iPad and below (≤ 1024px)
      if (screenWidth <= 1024 && navigator.share) {
        event.preventDefault();

        try {
          await navigator.share({
            title: shareText,
            text: shareText,
            url: shareUrl,
          });
          console.log('Partagé avec succès');
        } catch (error) {
          console.error('Erreur de partage:', error);
        }
      }
      // Else, desktop: no click event, normal hover animation
    });
  });
</script>