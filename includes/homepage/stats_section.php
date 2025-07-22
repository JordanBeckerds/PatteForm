<?php
// Fetch group_elem data from DB (assuming $pdo is your PDO connection)
$stmt = $pdo->query("SELECT * FROM group_elems LIMIT 1");
$group = $stmt->fetch(PDO::FETCH_ASSOC);

$logo = $group['logo'];

$color_primary = $group['color_primary'] ?? '#FFFFFF';      // For backgrounds except text
$color_secondary = $group['color_secondary'] ?? '#FEF4EE';   // For secondary backgrounds except text
$color_tertiary = $group['color_tertiary'] ?? '#F97316';     // For highlights except text

try {
    $stmtToAdopt = $pdo->query("SELECT COUNT(*) as total_to_adopt FROM animaux_a_adopter");
    $totalToAdopt = $stmtToAdopt->fetch(PDO::FETCH_ASSOC)['total_to_adopt'] ?? 0;

    $stmtAdopted = $pdo->query("SELECT COUNT(*) as total_adopted FROM animaux_adopter");
    $totalAdopted = $stmtAdopted->fetch(PDO::FETCH_ASSOC)['total_adopted'] ?? 0;

} catch (PDOException $e) {
    $totalToAdopt = 0;
    $totalAdopted = 0;
    error_log("Database error: " . $e->getMessage());
}

$year = (new DateTime($date_creation))->format('Y');
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Statistiques Animaux</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.x/dist/tailwind.min.css" rel="stylesheet" />
  <style>
    :root {
      --color_primary: <?= $color_primary ?>;
      --color_secondary: <?= $color_secondary ?>;
      --color_tertiary: <?= $color_tertiary ?>;
    }

    .bg-primary {
      background-color: var(--color_primary);
    }

    .bg-secondary {
      background-color: var(--color_secondary);
    }

    .bg-tertiary {
      background-color: var(--color_tertiary);
    }
  </style>
</head>
<body>

<div class="bg-primary py-16">
  <div class="container mx-auto px-4 grid grid-cols-1 md:grid-cols-3 gap-10 text-center">
    
    <!-- Animals to Adopt -->
    <div class="flex flex-col items-center">
      <img src="../assets/img/a_adopter.png" alt="Animaux à adopter" class="w-16 h-16 mb-4" />
      <span class="text-3xl text-black font-semibold tracking-wider"><?= number_format($totalToAdopt, 0, ',', ' ') ?></span>
      <p class="uppercase text-black text-sm font-semibold mt-1">Animaux à adopter</p>
    </div>

    <!-- Date de création -->
    <div class="flex flex-col items-center">
      <img src="../assets/img/date_creation.png" alt="Date de création" class="w-16 h-16 mb-4" />
      <span class="text-3xl text-black font-semibold tracking-wider"><?= $year ?></span>
      <p class="uppercase text-black text-sm font-semibold mt-1">Date de création</p>
    </div>

    <!-- Animaux adoptés -->
    <div class="flex flex-col items-center">
      <img src="../assets/img/adopter.png" alt="Animaux adoptés" class="w-16 h-16 mb-4" />
      <span class="text-3xl text-black font-semibold tracking-wider"><?= number_format($totalAdopted, 0, ',', ' ') ?></span>
      <p class="uppercase text-black text-sm font-semibold mt-1">Animaux adoptés</p>
    </div>

  </div>
</div>

</body>
</html>