<head>
  <!-- Swiper CDN -->
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"
  />

  <style>
    .perspective {
        perspective: 1000px;
    }
    .card-inner {
        transform-style: preserve-3d;
        transition: transform 0.6s ease-in-out;
        position: relative;
        width: 100%;
        height: 100%;
    }
    .card-inner.flipped {
        transform: rotateY(180deg);
    }
    .card-front,
    .card-back {
        backface-visibility: hidden;
        -webkit-backface-visibility: hidden;
        position: absolute;
        width: 100%;
        height: 100%;
        border-radius: 0.75rem;
        padding: 1rem;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .card-back {
        transform: rotateY(180deg);
    }

    .swiper {
        padding-bottom: 50px;
        position: relative;
        overflow: visible;
    }
    .swiper-slide {
        width: auto !important;
        display: flex;
        justify-content: center;
    }

    .swiper-button-prev,
    .swiper-button-next {
        color: black !important;
        width: 2.5rem;
        height: 2.5rem;
        top: 45%;
        transform: translateY(-50%);
        position: absolute;
        z-index: 10;
        cursor: pointer;
        border-radius: 9999px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background-color 0.3s ease;
    }

    .swiper-button-prev {
        left: calc(50% - 250px);
    }
    .swiper-button-next {
        right: calc(50% - 250px);
    }

    .swiper-slide-next .card-inner {
      transform: rotate(20deg);
    }

    @media (max-width: 1024px) {
      .swiper-slide {
        width: 60vw !important;
      }
      .relative.w-\[20vw\].h-\[60vh\] {
        width: 60vw !important;
        height: 60vh !important;
      }
      .swiper-button-prev {
        left: 1rem;
      }
      .swiper-button-next {
        right: 1rem;
      }
      .text-5xl {
        font-size: 2rem;
      }
    }

    @media (max-width: 640px) {
      .swiper-slide {
        width: 90vw !important;
      }
      .relative.w-\[20vw\].h-\[60vh\] {
        width: 90vw !important;
        height: 60vh !important;
      }
      .text-5xl {
        font-size: 1.75rem;
      }
    }
  </style>
</head>

<div class="p-6 mt-12 text-center mx-auto space-y-2 flex flex-col items-center">
  <h2 class="text-[5vh] sm:text-5xl leading-[8vh] mb-16 w-[90%] sm:w-[55%] text-[<?php echo $color_tertiary?>] font-bold">L’adoption responsable change autant la vie de l’animal que la vôtre</h2>
  <p class="text-[2.75vh] sm:text-base text-gray-600 flex flex-col">
    Attention, préalablement à l’adoption d’un animal, vous devez signer un <strong>Certificat d’engagement et de connaissance des besoins spécifiques de l’espèce.</strong>
  </p>
</div>

<!-- Container -->
<div style="background: #2A7B9B;background: linear-gradient(180deg,<?php echo $color_primary?>  50%, <?php echo $color_secondary?> 50%);" class="overflow-hidden w-full h-[100vh] mx-auto flex items-center">
  <div class="w-full swiper mySwiper relative">
    <div class="swiper-wrapper h-[80vh] flex items-center">
      <div class="swiper-slide">
        <div class="relative w-[20vw] h-[60vh] perspective">
          <div class="card-inner">
            <div class="card-front bg-[<?= $color_primary ?>] shadow-md flex flex-col items-center justify-between">
              <img
                src="https://www.la-spa.fr/app/app/uploads/2021/10/conditions-dadoption-deskop-2.jpg"
                class="mt-4 w-32 h-32 object-cover rounded-full"
              />
              <h3 class="text-center text-2xl font-bold">Les conditions d'adoption</h3>
              <button class="text-4xl font-bold cursor-pointer flip-btn">+</button>
            </div>
            <div class="card-back bg-[<?= $color_primary ?>] flex flex-col justify-between">
              <div class="h-[80%] flex justify-center items-center">
                <p class="text-black text-xl w-[80%] text-center">Vous devez être majeur et vous inscrire dans une démarche d'adoption responsable. Une participation financière vous sera demandée selon l’espèce et la situation de l’animal.  </p>
              </div>
              <div class="flex justify-between items-center">
                <span class="text-green-500 text-4xl">✔</span>
                <button class="text-4xl font-bold cursor-pointer flip-btn">−</button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="swiper-slide">
        <div class="relative w-[20vw] h-[60vh] perspective">
          <div class="card-inner">
            <div class="card-front bg-[<?= $color_primary ?>] shadow-md flex flex-col items-center justify-between">
              <img
                src="https://www.la-spa.fr/app/app/uploads/2021/10/conditions-dadoption-deskop-2.jpg"
                class="mt-4 w-32 h-32 object-cover rounded-full"
              />
              <h3 class="text-center text-2xl font-bold">L'adoption responsable</h3>
              <button class="text-4xl font-bold cursor-pointer flip-btn">+</button>
            </div>
            <div class="card-back bg-[<?= $color_primary ?>] flex flex-col justify-between">
              <div class="h-[80%] flex justify-center items-center">
                <p class="text-black text-xl w-[80%] text-center"><?php echo $name?> veille à ce que chaque décision soit mûrement réfléchie et que l’animal adopté corresponde à sa nouvelle famille et à son mode de vie. </p>
              </div>
              <div class="flex justify-between items-center">
                <span class="text-green-500 text-4xl">✔</span>
                <button class="text-4xl font-bold cursor-pointer flip-btn">−</button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="swiper-slide">
        <div class="relative w-[20vw] h-[60vh] perspective">
          <div class="card-inner">
            <div class="card-front bg-[<?= $color_primary ?>] shadow-md flex flex-col items-center justify-between">
              <img
                src="https://www.la-spa.fr/app/app/uploads/2021/10/conditions-dadoption-deskop-2.jpg"
                class="mt-4 w-32 h-32 object-cover rounded-full"
              />
              <h3 class="text-center text-2xl font-bold">L'adoption sauvetage</h3>
              <button class="text-4xl font-bold cursor-pointer flip-btn">+</button>
            </div>
            <div class="card-back bg-[<?= $color_primary ?>] flex flex-col justify-between">
              <div class="h-[80%] flex justify-center items-center">
                <p class="text-black text-xl w-[80%] text-center">A <?php echo $name?>, vous pouvez adopter pour sauver. Des animaux en échec d'adoption ou âgés ou encore victimes de pathologies lourdes attendent aussi la chaleur d'un foyer.</p>
              </div>
              <div class="flex justify-between items-center">
                <span class="text-green-500 text-4xl">✔</span>
                <button class="text-4xl font-bold cursor-pointer flip-btn">−</button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="swiper-slide">
        <div class="relative w-[20vw] h-[60vh] perspective">
          <div class="card-inner">
            <div class="card-front bg-[<?= $color_primary ?>] shadow-md flex flex-col items-center justify-between">
              <img
                src="https://www.la-spa.fr/app/app/uploads/2021/10/conditions-dadoption-deskop-2.jpg"
                class="mt-4 w-32 h-32 object-cover rounded-full"
              />
              <h3 class="text-center text-2xl font-bold">Le parcours d'adoption</h3>
              <button class="text-4xl font-bold cursor-pointer flip-btn">+</button>
            </div>
            <div class="card-back bg-[<?= $color_primary ?>] flex flex-col justify-between">
              <div class="h-[80%] flex justify-center items-center">
                <p class="text-black text-xl w-[80%] text-center">Pour adopter à <?php echo $name?>, vous devrez nous décrire votre mode de vie et vos attentes. Le formulaire adoptant est fait pour cela. Remplissez dès maintenant !</p>
              </div>
              <div class="flex justify-between items-center">
                <span class="text-green-500 text-4xl">✔</span>
                <button class="text-4xl font-bold cursor-pointer flip-btn">−</button>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Autres slides -->
    </div>

    <div class="swiper-button-prev"></div>
    <div class="swiper-button-next"></div>
  </div>
</div>

<div class="w-[100vw] h-[30vh] flex items-center justify-center">
    <h2 class="text-5xl w-[90%] sm:w-[60%] text-center"><strong><?php echo $name?></strong>, c’est actuellement <span class="text-[<?php echo $color_tertiary?>]"><?php echo number_format($totalToAdopt, 0, ',', ' ')?></span> animaux (chiens, chats et autres) à adopter</h2>
</div>


<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<script>
  document.addEventListener("DOMContentLoaded", function () {
    const swiper = new Swiper(".mySwiper", {
      slidesPerView: "auto",
      centeredSlides: true,
      spaceBetween: 150,
      navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
      },
      on: {
        slideChangeTransitionEnd: applyRotation,
        init: applyRotation
      }
    });

    // Flip Logic
    document.querySelectorAll(".flip-btn").forEach((button) => {
      button.addEventListener("click", (e) => {
        e.stopPropagation();
        const card = e.target.closest(".relative").querySelector(".card-inner");
        card.classList.toggle("flipped");
        applyRotation();
      });
    });

    function applyRotation() {
      document.querySelectorAll(".card-inner").forEach((el) => {
        const parent = el.closest(".swiper-slide");
        let transform = "";

        if (el.classList.contains("flipped")) {
          transform += " rotateY(180deg)";
        }

        if (parent.classList.contains("swiper-slide-next") && !el.classList.contains("flipped")) {
          transform += " rotate(20deg)";
        }

        el.style.transform = transform.trim();
      });
    }
  });
</script>