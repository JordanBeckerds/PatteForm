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
?>

<style>
  .line-clamp-16 {
  display: -webkit-box;
  -webkit-line-clamp: 14;
  -webkit-box-orient: vertical;  
  overflow: hidden;
}
</style>

<body class="min-h-screen">
  <section class="flex flex-col md:flex-row w-screen min-h-[80vh] p-24 bg-[<?= $color_secondary ?>] shadow-lg gap-[5vw] mb-48">
    <div class="md:w-1/2 flex flex-col md:pl-12 mt-6 md:mt-0">
      <time class="text-black text-lg mb-3"><?= date('d/m/Y', strtotime($actualite['date_publication'])) ?></time>
      <h1 class="text-4xl font-extrabold text-black mb-5"><?= htmlspecialchars($actualite['titre']) ?></h1>
      <p class="text-black text-2xl leading-relaxed whitespace-pre-wrap line-clamp-16"><?= nl2br(htmlspecialchars($actualite['description'])) ?>
      </p>
    </div>
    
    <?php if (!empty($actualite['img'])): ?>
      <div class="md:w-1/2 flex-shrink-0">
        <img src="<?= htmlspecialchars($actualite['img']) ?>" alt="<?= htmlspecialchars($actualite['titre']) ?>" class="w-full max-h-[60vh] object-contain rounded-xl" />
      </div>
    <?php endif; ?>
  </section>
</body>

<?php

$actualite_id = (int)($_GET['id'] ?? 0);
if ($actualite_id <= 0) {
    echo "<p class='text-center text-red-600'>Actualité invalide.</p>";
    return;
}

// Load main actualité
$sql = "SELECT * FROM actualite WHERE id = :id LIMIT 1";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $actualite_id]);
$actualite = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$actualite) {
    echo "<p class='text-center text-gray-600'>Aucune actualité trouvée.</p>";
    return;
}

// Load all ordered sections
$sql = "SELECT * FROM actualite_secs WHERE actualite_id = :id ORDER BY position ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $actualite_id]);
$sections = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($sections as $section) {
    $type = $section['type'];
    $section_id = (int)$section['id']; // actualite_secs.id

    switch ($type) {
        case 'carousel':
            $stmt = $pdo->prepare("SELECT * FROM carousel_secs WHERE actualite_sec_id = :sid LIMIT 1");
            $stmt->execute(['sid' => $section_id]);
            $carousel_sec = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($carousel_sec) {
                $stmt = $pdo->prepare("SELECT * FROM carousel_imgs WHERE carousel_id = :cid ORDER BY id ASC");
                $stmt->execute(['cid' => $carousel_sec['id']]);
                $carousel_images = $stmt->fetchAll(PDO::FETCH_ASSOC);

                include __DIR__ . "/elem/carousel.php";
            }
            break;

        case 'mosaic':
            $stmt = $pdo->prepare("SELECT * FROM mosaic_secs WHERE actualite_sec_id = :sid LIMIT 1");
            $stmt->execute(['sid' => $section_id]);
            $mosaic_sec = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($mosaic_sec) {
                $stmt = $pdo->prepare("SELECT * FROM mosaic_imgs WHERE mosaic_id = :mid ORDER BY id ASC");
                $stmt->execute(['mid' => $mosaic_sec['id']]);
                $mosaic_images = $stmt->fetchAll(PDO::FETCH_ASSOC);

                include __DIR__ . "/elem/mosaic.php";
            }
            break;

        case 'img':
            $stmt = $pdo->prepare("SELECT * FROM img_secs WHERE actualite_sec_id = :sid LIMIT 1");
            $stmt->execute(['sid' => $section_id]);
            $img_data = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($img_data) {
                include __DIR__ . "/elem/img.php";
            }
            break;

        case 'vid':
            $stmt = $pdo->prepare("SELECT * FROM vid_secs WHERE actualite_sec_id = :sid LIMIT 1");
            $stmt->execute(['sid' => $section_id]);
            $vid_data = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($vid_data) {
                include __DIR__ . "/elem/vid.php";
            }
            break;

        case 'desc':
            $stmt = $pdo->prepare("SELECT * FROM desc_secs WHERE actualite_sec_id = :sid LIMIT 1");
            $stmt->execute(['sid' => $section_id]);
            $desc_data = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($desc_data) {
                include __DIR__ . "/elem/desc.php";
            }
            break;

        default:
            echo "<p class='text-red-500'>Type inconnu : {$type}</p>";
            break;
    }
}
?>