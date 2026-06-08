<?php
// Included by public/adoption.php — no standalone <head> needed
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

<style>
  .perspective { perspective: 1000px; }
  .card-inner {
    transform-style: preserve-3d;
    transition: transform 0.6s ease-in-out;
    position: relative; width: 100%; height: 100%;
  }
  .card-inner.flipped { transform: rotateY(180deg); }
  .card-front, .card-back {
    backface-visibility: hidden;
    -webkit-backface-visibility: hidden;
    position: absolute; width: 100%; height: 100%;
    border-radius: 0.75rem; padding: 1rem;
    display: flex; flex-direction: column; justify-content: space-between;
  }
  .card-back { transform: rotateY(180deg); }
  .swiper { padding-bottom: 50px; position: relative; overflow: visible; }
  .swiper-slide { width: auto !important; display: flex; justify-content: center; }
  .swiper-button-prev, .swiper-button-next {
    color: black !important; width: 2.5rem; height: 2.5rem;
    top: 45%; transform: translateY(-50%); position: absolute;
    z-index: 10; cursor: pointer; border-radius: 9999px;
    display: flex; align-items: center; justify-content: center;
    transition: background-color 0.3s ease;
  }
  .swiper-button-prev { left: calc(50% - 250px); }
  .swiper-button-next { right: calc(50% - 250px); }
  .swiper-slide-next .card-inner { transform: rotate(20deg); }
  @media (max-width:1024px) {
    .swiper-slide { width:60vw !important; }
    .swiper-button-prev { left:1rem; }
    .swiper-button-next { right:1rem; }
    .text-5xl { font-size:2rem; }
  }
  @media (max-width:640px) {
    .swiper-slide { width:90vw !important; }
    .text-5xl { font-size:1.75rem; }
  }
</style>

<div class="p-6 mt-12 text-center mx-auto space-y-2 flex flex-col items-center">
  <h2 class="text-[5vh] sm:text-5xl leading-[8vh] mb-16 w-[90%] sm:w-[55%] font-bold"
      style="color:<?= htmlspecialchars($color_tertiary, ENT_QUOTES, 'UTF-8') ?>">
    L&#39;adoption responsable change autant la vie de l&#39;animal que la v&#244;tre
  </h2>
  <p class="text-[2.75vh] sm:text-base text-gray-600 flex flex-col">
    Attention, pr&#233;alablement &#224; l&#39;adoption d&#39;un animal, vous devez signer un
    <strong>Certificat d&#39;engagement et de connaissance des besoins sp&#233;cifiques de l&#39;esp&#232;ce.</strong>
  </p>
</div>

<div style="background:linear-gradient(180deg,<?= htmlspecialchars($color_primary, ENT_QUOTES, 'UTF-8') ?> 50%,<?= htmlspecialchars($color_secondary, ENT_QUOTES, 'UTF-8') ?> 50%)"
     class="overflow-hidden w-full h-[100vh] mx-auto flex items-center">
  <div class="w-full swiper mySwiper relative">
    <div class="swiper-wrapper h-[80vh] flex items-center">

      <?php
      $cards = [
        ['title' => 'Les conditions d&#39;adoption',  'back' => 'Vous devez &#234;tre majeur et vous inscrire dans une d&#233;marche d&#39;adoption responsable. Une participation financi&#232;re vous sera demand&#233;e selon l&#39;esp&#232;ce et la situation de l&#39;animal.'],
        ['title' => 'L&#39;adoption responsable',      'back' => htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . ' veille &#224; ce que chaque d&#233;cision soit m&#251;rement r&#233;fl&#233;chie et que l&#39;animal adopt&#233; corresponde &#224; sa nouvelle famille et &#224; son mode de vie.'],
        ['title' => 'L&#39;adoption sauvetage',        'back' => 'A ' . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . ', vous pouvez adopter pour sauver. Des animaux en &#233;chec d&#39;adoption ou &#226;g&#233;s ou victimes de pathologies lourdes attendent aussi la chaleur d&#39;un foyer.'],
        ['title' => 'Le parcours d&#39;adoption',      'back' => 'Pour adopter &#224; ' . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . ', vous devrez nous d&#233;crire votre mode de vie et vos attentes. Le formulaire adoptant est fait pour cela. Remplissez d&#232;s maintenant&#160;!'],
      ];
      foreach ($cards as $card):
      ?>
        <div class="swiper-slide">
          <div class="relative w-[20vw] h-[60vh] perspective">
            <div class="card-inner">
              <div class="card-front shadow-md flex flex-col items-center justify-between"
                   style="background-color:<?= htmlspecialchars($color_primary, ENT_QUOTES, 'UTF-8') ?>">
                <img src="https://www.la-spa.fr/app/app/uploads/2021/10/conditions-dadoption-deskop-2.jpg"
                     class="mt-4 w-32 h-32 object-cover rounded-full" alt="" />
                <h3 class="text-center text-2xl font-bold"><?= $card['title'] ?></h3>
                <button class="text-4xl font-bold cursor-pointer flip-btn">+</button>
              </div>
              <div class="card-back flex flex-col justify-between"
                   style="background-color:<?= htmlspecialchars($color_primary, ENT_QUOTES, 'UTF-8') ?>">
                <div class="h-[80%] flex justify-center items-center">
                  <p class="text-black text-xl w-[80%] text-center"><?= $card['back'] ?></p>
                </div>
                <div class="flex justify-between items-center">
                  <span class="text-green-500 text-4xl">&#10004;</span>
                  <button class="text-4xl font-bold cursor-pointer flip-btn">&#8722;</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>

    </div>
    <div class="swiper-button-prev"></div>
    <div class="swiper-button-next"></div>
  </div>
</div>

<div class="w-[100vw] h-[30vh] flex items-center justify-center">
  <h2 class="text-5xl w-[90%] sm:w-[60%] text-center">
    <strong><?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?></strong>, c&#39;est actuellement
    <span style="color:<?= htmlspecialchars($color_tertiary, ENT_QUOTES, 'UTF-8') ?>"><?= number_format((int)$totalToAdopt, 0, ',', '&#160;') ?></span>
    animaux (chiens, chats et autres) &#224; adopter
  </h2>
</div>

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const swiper = new Swiper('.mySwiper', {
      slidesPerView: 'auto',
      centeredSlides: true,
      spaceBetween: 150,
      navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
      on: { slideChangeTransitionEnd: applyRotation, init: applyRotation }
    });

    document.querySelectorAll('.flip-btn').forEach(btn => {
      btn.addEventListener('click', e => {
        e.stopPropagation();
        e.target.closest('.relative').querySelector('.card-inner').classList.toggle('flipped');
        applyRotation();
      });
    });

    function applyRotation() {
      document.querySelectorAll('.card-inner').forEach(el => {
        const parent = el.closest('.swiper-slide');
        let t = '';
        if (el.classList.contains('flipped'))                                          t += ' rotateY(180deg)';
        if (parent.classList.contains('swiper-slide-next') && !el.classList.contains('flipped')) t += ' rotate(20deg)';
        el.style.transform = t.trim();
      });
    }
  });
</script>
