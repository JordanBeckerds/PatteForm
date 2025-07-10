<?php

$actualite_id = (int)($_GET['id'] ?? 0);
if ($actualite_id <= 0) {
    echo "<p class='text-center text-red-600'>Actualité invalide.</p>";
    return;
}

// Fetch main actualite info
$sql = "SELECT * FROM actualite WHERE id = :id LIMIT 1";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $actualite_id]);
$actualite = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$actualite) {
    echo "<p class='text-center text-gray-600'>Aucune actualité trouvée.</p>";
    return;
}

// Fetch carousel images
$sql = "SELECT * FROM actualite_carousel_images WHERE actualite_id = :id ORDER BY position ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $actualite_id]);
$carousel_images = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch additional description sections
$sql = "SELECT * FROM actualite_descriptions WHERE actualite_id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $actualite_id]);
$descriptions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch additional images
$sql = "SELECT * FROM actualite_images WHERE actualite_id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $actualite_id]);
$additional_images = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch videos
$sql = "SELECT * FROM actualite_videos WHERE actualite_id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $actualite_id]);
$videos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<body class="bg-gray-50 min-h-screen">
  <section class="flex flex-col md:flex-row w-screen min-h-[70vh] p-10 bg-white shadow-lg">
    <div class="md:w-1/2 flex flex-col md:pl-12 mt-6 md:mt-0">
      <time class="text-gray-500 text-lg mb-3"><?= date('d/m/Y', strtotime($actualite['date'])) ?></time>
      <h1 class="text-4xl font-extrabold text-gray-900 mb-5"><?= htmlspecialchars($actualite['titre']) ?></h1>
      <p class="text-gray-700 leading-relaxed whitespace-pre-wrap"><?= nl2br(htmlspecialchars($actualite['description'])) ?></p>
    </div>
    
    <?php if (!empty($actualite['img'])): ?>
      <div class="md:w-1/2 flex-shrink-0">
        <img src="<?= htmlspecialchars($actualite['img']) ?>" alt="<?= htmlspecialchars($actualite['titre']) ?>" class="w-full h-full object-cover rounded-xl" />
      </div>
    <?php endif; ?>
  </section>

<?php include '../includes/actualites/elem/carousel.php'; ?>

<!-- Additional Description Sections -->
<?php if (!empty($descriptions)): ?>
  <?php
  // Function to make URLs clickable
  function makeLinksClickable($text) {
    $text = htmlspecialchars($text); // escape HTML
    $text = preg_replace_callback(
      '/(https?:\/\/[^\s]+)/',
      function ($matches) {
        $url = $matches[1];
        return '<a href="' . $url . '" class="text-blue-600 underline hover:text-blue-800" target="_blank" rel="noopener noreferrer">' . $url . '</a>';
      },
      $text
    );
    return nl2br($text); // preserve line breaks
  }
  ?>
  <section class="w-screen bg-[#F3F4F6] py-12">
    <div class="max-w-4xl mx-auto px-6 md:px-8">
      <?php foreach ($descriptions as $desc): ?>
        <div class="mb-12">
          <?php if (!empty($desc['section_title'])): ?>
            <h3 class="text-3xl font-semibold text-gray-900 mb-4"><?= htmlspecialchars($desc['section_title']) ?></h3>
          <?php endif; ?>
          <p class="text-gray-700 leading-relaxed text-lg">
            <?= makeLinksClickable($desc['section_text']) ?>
          </p>
        </div>
      <?php endforeach; ?>
    </div>
  </section>
<?php endif; ?>

  <!-- Additional Images -->
  <?php if (!empty($additional_images)): ?>
  <section class="max-w-6xl mx-auto px-6 py-12 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
    <?php foreach ($additional_images as $img): ?>
      <img src="<?= htmlspecialchars($img['img_url']) ?>" alt="Image supplémentaire" class="rounded-xl object-cover w-full h-48 shadow-md" />
    <?php endforeach; ?>
  </section>
  <?php endif; ?>

  <!-- Videos -->
  <?php if (!empty($videos)): ?>
  <section class="max-w-6xl mx-auto px-6 py-12">
    <h2 class="text-3xl font-bold mb-6 text-gray-900">Vidéos</h2>
    <div class="space-y-12">
      <?php foreach ($videos as $video): 
        $video_url = $video['video_url'];
        $youtube_id = null;

        if (preg_match('/youtu\.be\/([^\?&]+)/', $video_url, $matches)) {
          $youtube_id = $matches[1];
        } elseif (preg_match('/youtube\.com.*v=([^&]+)/', $video_url, $matches)) {
          $youtube_id = $matches[1];
        }
      ?>
        <div class="aspect-w-16 aspect-h-9 max-h-[720px]">
          <?php if ($youtube_id): ?>
            <iframe 
              class="w-full min-h-[70vh] rounded-xl shadow-lg" 
              src="https://www.youtube.com/embed/<?= htmlspecialchars($youtube_id) ?>" 
              title="YouTube video player" 
              frameborder="0" 
              allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
              allowfullscreen></iframe>
          <?php else: ?>
            <video controls class="w-full h-full rounded-xl shadow-lg max-h-[720px]">
              <source src="<?= htmlspecialchars($video_url) ?>" type="video/mp4" />
              Your browser does not support the video tag.
            </video>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>
  </section>
  <?php endif; ?>

  <div class="max-w-7xl mx-auto px-6 py-8">
    <a href="actualites.php" class="inline-block text-[#F97316] font-semibold hover:underline">
      &larr; Retour aux actualités
    </a>
  </div>
</body>