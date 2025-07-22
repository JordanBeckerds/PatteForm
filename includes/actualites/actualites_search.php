<?php

$sql = "SELECT * FROM actualite ORDER BY date_publication DESC";
$stmt = $pdo->query($sql);
$actualites = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Actualités - Patteform</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    function filterActualites() {
      const query = document.getElementById('search').value.toLowerCase();
      const cards = document.querySelectorAll('.actualite-card');

      cards.forEach(card => {
        const title = card.querySelector('.actualite-title').textContent.toLowerCase();
        const description = card.querySelector('.actualite-desc').textContent.toLowerCase();

        if (title.includes(query) || description.includes(query)) {
          card.classList.remove('hidden');
        } else {
          card.classList.add('hidden');
        }
      });
    }
  </script>
</head>
<body class="text-gray-800 font-sans min-h-screen">

<main class="container mx-auto px-6 py-16 flex flex-col md:flex-row gap-16 min-h-[100vh]">
  <!-- Search Bar -->
  <aside class="w-full md:w-1/4 bg-white rounded-2xl shadow-lg p-8 md:sticky md:top-56 h-fit">
    <label for="search" class="block mb-6 font-semibold text-2xl text-gray-700">Rechercher Actualités</label>
    <input
      type="text"
      id="search"
      onkeyup="filterActualites()"
      placeholder="Tapez un mot-clé..."
      class="w-full px-6 py-4 border border-gray-300 rounded-xl focus:outline-none focus:ring-4 focus:ring-[<?= $color_tertiary ?>] focus:border-[<?= $color_tertiary ?>] text-lg"
      autocomplete="off"
    />
  </aside>

  <!-- Actualités List -->
  <section class="w-full md:w-3/4 grid grid-cols-1 gap-20">
    <?php if (count($actualites) === 0): ?>
      <p class="text-center text-gray-500 text-2xl">Aucune actualité trouvée.</p>
    <?php else: ?>
      <?php foreach ($actualites as $act): ?>
        <a href="actualites.php?id=<?= $act['id'] ?>" class="block">
          <article class="actualite-card bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col md:flex-row gap-8 md:gap-14 p-8 md:p-14 transition hover:shadow-[0_15px_40px_<?= $color_tertiary ?>] min-h-[300px] hover:cursor-pointer">
            <?php if (!empty($act['img'])): ?>
              <img src="<?= htmlspecialchars($act['img']) ?>" alt="<?= htmlspecialchars($act['titre']) ?>" 
                   class="w-full md:w-[380px] h-[200px] md:h-[300px] object-cover rounded-xl flex-shrink-0" />
            <?php endif; ?>

            <div class="flex flex-col justify-between w-full">
              <h2 class="actualite-title text-2xl md:text-4xl font-extrabold text-gray-900 mb-3 md:mb-4"><?= htmlspecialchars($act['titre']) ?></h2>
              <p class="actualite-desc text-gray-800 mb-4 md:mb-6 text-base md:text-xl leading-relaxed line-clamp-6"><?= nl2br(htmlspecialchars($act['description'])) ?></p>
              <time class="text-sm md:text-lg text-black"><?= date('d/m/Y', strtotime($act['date_publication'])) ?></time>
            </div>
          </article>
        </a>
      <?php endforeach; ?>
    <?php endif; ?>
  </section>
</main>

</body>
</html>