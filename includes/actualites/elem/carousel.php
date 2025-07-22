<section class="max-w-6xl mx-auto px-6 md:px-20 pb-48 relative">
  <div id="carousel" class="relative w-full h-[60vh] ">
    <div class="relative w-full h-full">
      <?php foreach ($carousel_images as $index => $cimg): ?>
        <img
          src="<?= htmlspecialchars($cimg['img_url']) ?>"
          alt="Image du carrousel <?= $index + 1 ?>"
          class="carousel-img absolute top-0 left-0 w-full h-full object-contain transition-opacity duration-700 ease-in-out <?= $index === 0 ? 'opacity-100 z-10' : 'opacity-0 z-0 pointer-events-none' ?>"
          data-index="<?= $index ?>"
        />
      <?php endforeach; ?>
    </div>

    <!-- Buttons, always present, but hidden if only 1 image -->
    <button id="prevBtn" aria-label="Slide précédent"
      class="bg-[<?= $color_secondary ?>] absolute top-1/2 left-2 -translate-y-1/2 rounded-full p-3 shadow-lg text-3xl font-bold z-20 text-white
             transition duration-300 hover:brightness-110 hover:scale-110">
      &#10094;
    </button>

    <button id="nextBtn" aria-label="Slide suivant"
      class="bg-[<?= $color_secondary ?>] absolute top-1/2 right-2 -translate-y-1/2 rounded-full p-3 shadow-lg text-3xl font-bold z-20 text-white
             transition duration-300 hover:brightness-110 hover:scale-110">
      &#10095;
    </button>
  </div>
</section>

<script>
  (function () {
    const images = document.querySelectorAll('#carousel .carousel-img');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    let current = 0;
    const total = images.length;

    // Hide buttons if only 1 image
    if (total <= 1) {
      prevBtn.style.display = 'none';
      nextBtn.style.display = 'none';
    }

    function showImage(index) {
      images.forEach((img, i) => {
        img.classList.remove('opacity-100', 'z-10');
        img.classList.add('opacity-0', 'z-0', 'pointer-events-none');
        if (i === index) {
          img.classList.remove('opacity-0', 'z-0', 'pointer-events-none');
          img.classList.add('opacity-100', 'z-10');
        }
      });
    }

    prevBtn.addEventListener('click', () => {
      current = (current - 1 + total) % total;
      showImage(current);
    });

    nextBtn.addEventListener('click', () => {
      current = (current + 1) % total;
      showImage(current);
    });
  })();
</script>