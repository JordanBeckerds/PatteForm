<?php
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<p>Identifiant d'animal invalide.</p>";
    exit;
}

$id = (int)$_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM animaux_a_adopter WHERE id = ?");
$stmt->execute([$id]);
$animal = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$animal) {
    echo "<p>Animal non trouvé.</p>";
    exit;
}

$stmtImg = $pdo->prepare("SELECT img FROM photo_chiens WHERE id = ?");
$stmtImg->execute([$id]);
$images = $stmtImg->fetchAll(PDO::FETCH_COLUMN);

if (empty($images)) {
    $images = ['https://via.placeholder.com/600x400?text=No+Image'];
}
?>

<!-- TOP SECTION -->
<div class="w-full flex flex-col md:flex-row items-center justify-between gap-8 px-4 sm:px-6 md:px-72 py-8 md:py-12 bg-[#FEF4EE] rounded-lg min-h-[70vh]">

  <!-- LEFT: Text Content -->
  <div class="w-full md:w-1/2 flex flex-col justify-center space-y-4 md:space-y-6">
    <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold text-[#F97316]"><?= htmlspecialchars($animal['nom']) ?></h2>
    <p class="text-xl sm:text-2xl md:text-3xl text-gray-700"><?= htmlspecialchars($animal['race']) ?> - <?= htmlspecialchars($animal['age']) ?> an(s)</p>

    <ul class="list-disc pl-5 text-base sm:text-lg md:text-xl text-gray-600">
      <li><strong>Sexe:</strong> <?= htmlspecialchars($animal['sexe']) ?></li>
      <li><strong>Espèce:</strong> <?= htmlspecialchars($animal['espece']) ?></li>
      <li><strong>Ami des enfants:</strong> <?= $animal['enfant'] ? 'Oui' : 'Non' ?></li>
      <li><strong>Ami des chiens:</strong> <?= $animal['chien'] ? 'Oui' : 'Non' ?></li>
      <li><strong>Ami des chats:</strong> <?= $animal['chat'] ? 'Oui' : 'Non' ?></li>
      <li><strong>Ami des autres animaux:</strong> <?= $animal['autre'] ? 'Oui' : 'Non' ?></li>
    </ul>
  </div>

  <!-- RIGHT: Carousel -->
  <div class="relative w-full md:w-1/2 flex items-center justify-center gap-4 mb-6 md:mb-0">
    
    <!-- Left Arrow -->
    <button onclick="prevImage()" 
      class="flex md:w-14 md:h-14 md:text-4xl w-10 h-10 text-3xl items-center justify-center rounded-full bg-orange-500 text-white font-bold hover:bg-orange-600 transition leading-none" 
      aria-label="Précédent"
      style="align-self: center;">
      ‹
    </button>

    <!-- Carousel Container -->
    <div class="relative w-full max-w-3xl aspect-[4/3] overflow-hidden bg-transparent rounded">
      <div id="carousel" class="relative w-full h-full">
        <?php foreach ($images as $index => $img): ?>
          <img 
            src="<?= htmlspecialchars($img) ?>" 
            class="carousel-img absolute inset-0 w-full h-full object-contain transition-opacity duration-700 <?= $index === 0 ? 'opacity-100 z-10' : 'opacity-0 z-0' ?>" 
            data-index="<?= $index ?>"
            alt="Photo <?= $index + 1 ?>"
            style="background: transparent;"
          >
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Right Arrow -->
    <button onclick="nextImage()" 
      class="flex md:w-14 md:h-14 md:text-4xl w-10 h-10 text-3xl items-center justify-center rounded-full bg-orange-500 text-white font-bold hover:bg-orange-600 transition leading-none" 
      aria-label="Suivant"
      style="align-self: center;">
      ›
    </button>
  </div>
</div>

<!-- DESCRIPTION SECTION -->
<div class="w-full bg-white px-4 sm:px-6 md:px-72 pt-10 pb-16 md:pb-12 -mt-8 min-h-[200px] md:min-h-[500px]">
  <h3 class="text-2xl sm:text-3xl font-semibold text-gray-800 mb-4 mt-6 max-w-4xl mx-auto">Description</h3>
  <p class="text-base sm:text-xl text-gray-700 leading-relaxed max-w-4xl mx-auto text-left mt-4">
    <?= nl2br(htmlspecialchars($animal['description'])) ?>
  </p>
</div>

<!-- BUTTON BELOW EVERYTHING -->
<div class="w-full bg-white flex justify-center pt-4 pb-40 px-4 sm:px-6 md:px-72">
  <button class="px-14 py-5 bg-orange-500 hover:bg-orange-600 text-white font-semibold rounded-lg transition duration-300 max-w-md w-full text-center">
    Rencontrer L'animal
  </button>
</div>

<script>
  const images = document.querySelectorAll('.carousel-img');
  let currentIndex = 0;

  function showImage(index) {
    images.forEach((img, i) => {
      img.style.opacity = (i === index) ? '1' : '0';
      img.style.zIndex = (i === index) ? '10' : '0';
    });
    currentIndex = index;
  }

  function prevImage() {
    const newIndex = (currentIndex === 0) ? images.length - 1 : currentIndex - 1;
    showImage(newIndex);
  }

  function nextImage() {
    const newIndex = (currentIndex === images.length - 1) ? 0 : currentIndex + 1;
    showImage(newIndex);
  }
</script>