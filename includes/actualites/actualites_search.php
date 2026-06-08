<?php
// Included by public/actualites.php — no standalone HTML wrapper needed

$sql   = 'SELECT * FROM actualite ORDER BY date_publication DESC';
$stmt  = $pdo->query($sql);
$actualites = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="container mx-auto px-6 py-16 flex flex-col md:flex-row gap-16 min-h-[100vh]">
  <!-- Search Bar -->
  <aside class="w-full md:w-1/4 bg-white rounded-2xl shadow-lg p-8 md:sticky md:top-56 h-fit">
    <label for="search" class="block mb-6 font-semibold text-2xl text-gray-700">Rechercher Actualit&#233;s</label>
    <input
      type="text"
      id="search"
      onkeyup="filterActualites()"
      placeholder="Tapez un mot-cl&#233;..."
      class="w-full px-6 py-4 border border-gray-300 rounded-xl focus:outline-none focus:ring-4
             focus:ring-[<?= htmlspecialchars($color_tertiary, ENT_QUOTES, 'UTF-8') ?>]
             focus:border-[<?= htmlspecialchars($color_tertiary, ENT_QUOTES, 'UTF-8') ?>] text-lg"
      autocomplete="off"
    />
  </aside>

  <!-- Actualit&#233;s List -->
  <section class="w-full md:w-3/4 grid grid-cols-1 gap-20">
    <?php if (count($actualites) === 0): ?>
      <p class="text-center text-gray-500 text-2xl">Aucune actualit&#233; trouv&#233;e.</p>
    <?php else: ?>
      <?php foreach ($actualites as $act): ?>
        <a href="actualites.php?id=<?= (int)$act['id'] ?>" class="block">
          <article class="actualite-card bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col md:flex-row gap-8 md:gap-14 p-8 md:p-14 transition hover:shadow-xl min-h-[300px] hover:cursor-pointer">
            <?php if (!empty($act['img'])): ?>
              <img src="<?= htmlspecialchars($act['img'], ENT_QUOTES, 'UTF-8') ?>"
                   alt="<?= htmlspecialchars($act['titre'], ENT_QUOTES, 'UTF-8') ?>"
                   class="w-full md:w-[380px] h-[200px] md:h-[300px] object-cover rounded-xl flex-shrink-0" />
            <?php endif; ?>
            <div class="flex flex-col justify-between w-full">
              <h2 class="actualite-title text-2xl md:text-4xl font-extrabold text-gray-900 mb-3 md:mb-4">
                <?= htmlspecialchars($act['titre'], ENT_QUOTES, 'UTF-8') ?>
              </h2>
              <p class="actualite-desc text-gray-800 mb-4 md:mb-6 text-base md:text-xl leading-relaxed line-clamp-6">
                <?= nl2br(htmlspecialchars($act['description'], ENT_QUOTES, 'UTF-8')) ?>
              </p>
              <time class="text-sm md:text-lg text-black">
                <?= htmlspecialchars(date('d/m/Y', strtotime($act['date_publication'])), ENT_QUOTES, 'UTF-8') ?>
              </time>
            </div>
          </article>
        </a>
      <?php endforeach; ?>
    <?php endif; ?>
  </section>
</main>

<script>
  function filterActualites() {
    const query = document.getElementById('search').value.toLowerCase();
    document.querySelectorAll('.actualite-card').forEach(card => {
      const title = card.querySelector('.actualite-title').textContent.toLowerCase();
      const desc  = card.querySelector('.actualite-desc').textContent.toLowerCase();
      card.closest('a').classList.toggle('hidden', !title.includes(query) && !desc.includes(query));
    });
  }
</script>
