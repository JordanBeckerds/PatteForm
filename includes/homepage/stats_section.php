<?php
// Include your config file for PDO connection

try {
    // Prepare and execute queries to get counts
    $stmtToAdopt = $pdo->query("SELECT COUNT(*) as total_to_adopt FROM animaux_a_adopter");
    $totalToAdopt = $stmtToAdopt->fetch(PDO::FETCH_ASSOC)['total_to_adopt'] ?? 0;

    $stmtAdopted = $pdo->query("SELECT COUNT(*) as total_adopted FROM animaux_adopter");
    $totalAdopted = $stmtAdopted->fetch(PDO::FETCH_ASSOC)['total_adopted'] ?? 0;

} catch (PDOException $e) {
    // Handle error gracefully
    $totalToAdopt = 0;
    $totalAdopted = 0;
    error_log("Database error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Statistiques Animaux</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.x/dist/tailwind.min.css" rel="stylesheet" />
</head>
<body>

<div class="bg-[#ffffff] py-16">
  <div class="container mx-auto px-4 grid grid-cols-1 md:grid-cols-3 gap-10 text-center">
    
    <!-- Animals to Adopt -->
    <div class="flex flex-col items-center">
      <img src="../assets/img/a_adopter.png" alt="Animaux à adopter" class="w-16 h-16 mb-4" />
      <span class="text-3xl font-semibold tracking-wider"><?= number_format($totalToAdopt, 0, ',', ' ') ?></span>
      <p class="uppercase text-sm font-semibold mt-1">Animaux à adopter</p>
    </div>

    <!-- Date de création -->
    <div class="flex flex-col items-center">
      <img src="../assets/img/date_creation.png" alt="Date de création" class="w-16 h-16 mb-4" />
      <span class="text-3xl font-semibold tracking-wider">2023</span>
      <p class="uppercase text-sm font-semibold mt-1">Date de création</p>
    </div>

    <!-- Animaux adoptés -->
    <div class="flex flex-col items-center">
      <img src="../assets/img/adopter.png" alt="Animaux adoptés" class="w-16 h-16 mb-4" />
      <span class="text-3xl font-semibold tracking-wider"><?= number_format($totalAdopted, 0, ',', ' ') ?></span>
      <p class="uppercase text-sm font-semibold mt-1">Animaux adoptés</p>
    </div>

  </div>
</div>

</body>
</html>