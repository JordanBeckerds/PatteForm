<?php
// Included by public pages — no standalone HTML wrapper

$stmtGroup = $pdo->query('SELECT * FROM group_elems LIMIT 1');
$groupStats = $stmtGroup->fetch(PDO::FETCH_ASSOC);

$color_primary_s   = $groupStats['color_primary']   ?? '#FFFFFF';
$color_secondary_s = $groupStats['color_secondary'] ?? '#FEF4EE';
$color_tertiary_s  = $groupStats['color_tertiary']  ?? '#F97316';

try {
    $totalToAdoptS = $pdo->query('SELECT COUNT(*) FROM animaux_a_adopter')->fetchColumn();
    $totalAdoptedS = $pdo->query('SELECT COUNT(*) FROM animaux_adopter')->fetchColumn();
} catch (PDOException $e) {
    $totalToAdoptS = $totalAdoptedS = 0;
    error_log('PatteForm stats: ' . $e->getMessage());
}

$dc = $groupStats['date_creation'] ?? null;
$yearS = $dc ? (new DateTime($dc))->format('Y') : date('Y');
?>

<div style="background-color:<?= htmlspecialchars($color_primary_s, ENT_QUOTES, 'UTF-8') ?>" class="py-16">
  <div class="container mx-auto px-4 grid grid-cols-1 md:grid-cols-3 gap-10 text-center">

    <div class="flex flex-col items-center">
      <img src="../assets/img/a_adopter.png" alt="Animaux &#224; adopter" class="w-16 h-16 mb-4" />
      <span class="text-3xl text-black font-semibold tracking-wider"><?= (int)$totalToAdoptS ?></span>
      <p class="uppercase text-black text-sm font-semibold mt-1">Animaux &#224; adopter</p>
    </div>

    <div class="flex flex-col items-center">
      <img src="../assets/img/date_creation.png" alt="Date de cr&#233;ation" class="w-16 h-16 mb-4" />
      <span class="text-3xl text-black font-semibold tracking-wider"><?= htmlspecialchars($yearS, ENT_QUOTES, 'UTF-8') ?></span>
      <p class="uppercase text-black text-sm font-semibold mt-1">Date de cr&#233;ation</p>
    </div>

    <div class="flex flex-col items-center">
      <img src="../assets/img/adopter.png" alt="Animaux adopt&#233;s" class="w-16 h-16 mb-4" />
      <span class="text-3xl text-black font-semibold tracking-wider"><?= (int)$totalAdoptedS ?></span>
      <p class="uppercase text-black text-sm font-semibold mt-1">Animaux adopt&#233;s</p>
    </div>

  </div>
</div>
