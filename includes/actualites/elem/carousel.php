<!-- Carousel Section -->
  <?php if (!empty($carousel_images)): ?>
  <section class="max-w-6xl mx-auto px-6 md:px-20 py-12 relative">
    <div id="carousel" class="relative w-full h-[60vh] overflow-hidden shadow-lg">
      <div id="carousel-inner" class="flex transition-transform duration-500 ease-in-out" style="width: <?= count($carousel_images) * 100 ?>%;">
        <?php foreach ($carousel_images as $cimg): ?>
          <div class="carousel-item flex-shrink-0 w-full">
            <img src="<?= htmlspecialchars($cimg['img_url']) ?>" alt="Carousel image" class=" max-h-[60vh] object-contain " />
          </div>
        <?php endforeach; ?>
      </div>

      <!-- Controls -->
      <button id="prevBtn" aria-label="Previous slide"
        style="background-color:#F97316;"
        class="absolute top-1/2 left-2 -translate-y-1/2 rounded-full p-3 shadow-lg text-3xl font-bold z-10 select-none text-white
              transition duration-300 ease-in-out hover:brightness-110 hover:scale-110">
        &#10094;
      </button>

      <button id="nextBtn" aria-label="Next slide"
        style="background-color:#F97316;"
        class="absolute top-1/2 right-2 -translate-y-1/2 rounded-full p-3 shadow-lg text-3xl font-bold z-10 select-none text-white
              transition duration-300 ease-in-out hover:brightness-110 hover:scale-110">
        &#10095;
      </button>
    </div>
  </section>


  <script>
    (function() {
      const carouselInner = document.getElementById('carousel-inner');
      const prevBtn = document.getElementById('prevBtn');
      const nextBtn = document.getElementById('nextBtn');
      const totalItems = <?= count($carousel_images) ?>;
      let currentIndex = 0;

      function updateCarousel() {
        carouselInner.style.transform = `translateX(-${currentIndex * 100}%)`;
      }

      prevBtn.addEventListener('click', () => {
        currentIndex = (currentIndex - 1 + totalItems) % totalItems;
        updateCarousel();
      });

      nextBtn.addEventListener('click', () => {
        currentIndex = (currentIndex + 1) % totalItems;
        updateCarousel();
      });
    })();
  </script>
<?php endif; ?>