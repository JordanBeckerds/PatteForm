<?php
// Dashboard home — included by public/dashboard.php

// Safe whitelist of tables to count
$tableMap = [
    'Actualit&#233;s'       => 'actualite',
    'Animaux'            => 'animaux_a_adopter',
    'Sections Accueil'   => 'homepage_sections',
    '&#201;quipe'         => 'equipe',
    'Utilisateurs'       => 'users',
];

$stats = [];
foreach ($tableMap as $label => $table) {
    try {
        $count = $pdo->query("SELECT COUNT(*) FROM `$table`")->fetchColumn();
    } catch (PDOException $e) {
        $count = 0;
    }
    $stats[$label] = (int)$count;
}

$recent_act    = $pdo->query('SELECT * FROM actualite ORDER BY date_publication DESC LIMIT 3')->fetchAll();
$recent_animaux = $pdo->query('SELECT * FROM animaux_a_adopter ORDER BY date_arriver DESC LIMIT 3')->fetchAll();

$ct = htmlspecialchars($color_tertiary,  ENT_QUOTES, 'UTF-8');
$cs = htmlspecialchars($color_secondary, ENT_QUOTES, 'UTF-8');
$cp = htmlspecialchars($color_primary,   ENT_QUOTES, 'UTF-8');
?>

<h1 class="text-3xl font-bold mb-6" style="color:<?= $ct ?>">Bienvenue&#160;!</h1>
<p class="mb-6 text-gray-700">Voici un aper&#231;u rapide de votre dashboard.</p>

<div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-6 mb-8">
  <?php foreach ($stats as $label => $count):
    $pageKey = strtolower(str_replace(['&#233;', '&#201;', ' ', '&#8200;'], ['e', 'e', '_', ''], html_entity_decode($label)));
  ?>
    <div class="p-6 rounded shadow hover:shadow-lg transition flex flex-col items-center justify-center"
         style="background-color:<?= $cp ?>">
      <div class="text-4xl font-bold" style="color:<?= $ct ?>"><?= $count ?></div>
      <div class="mt-2 text-gray-700 font-semibold"><?= $label ?></div>
    </div>
  <?php endforeach; ?>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
  <div class="bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-4" style="color:<?= $ct ?>">Actualit&#233;s r&#233;centes</h2>
    <ul class="space-y-2">
      <?php foreach ($recent_act as $act): ?>
        <li class="border-b py-2 flex justify-between items-center">
          <span><?= htmlspecialchars($act['titre'], ENT_QUOTES, 'UTF-8') ?></span>
          <span class="text-gray-500 text-sm"><?= htmlspecialchars($act['date_publication'], ENT_QUOTES, 'UTF-8') ?></span>
        </li>
      <?php endforeach; ?>
      <?php if (empty($recent_act)): ?>
        <li class="text-gray-400">Aucune actualit&#233; pour le moment.</li>
      <?php endif; ?>
    </ul>
  </div>

  <div class="bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-4" style="color:<?= $ct ?>">Arriv&#233;es r&#233;centes</h2>
    <ul class="space-y-2">
      <?php foreach ($recent_animaux as $dog): ?>
        <li class="border-b py-2 flex justify-between items-center">
          <span><?= htmlspecialchars($dog['nom'], ENT_QUOTES, 'UTF-8') ?> (<?= htmlspecialchars($dog['espece'], ENT_QUOTES, 'UTF-8') ?>)</span>
          <span class="text-gray-500 text-sm"><?= htmlspecialchars($dog['date_arriver'], ENT_QUOTES, 'UTF-8') ?></span>
        </li>
      <?php endforeach; ?>
      <?php if (empty($recent_animaux)): ?>
        <li class="text-gray-400">Aucune arriv&#233;e r&#233;cente.</li>
      <?php endif; ?>
    </ul>
  </div>
</div>

<div class="mt-8 p-6 rounded shadow" style="background-color:<?= $cs ?>">
  <h2 class="text-xl font-bold mb-2" style="color:<?= $ct ?>">Conseils rapides</h2>
  <ul class="list-disc pl-5 text-gray-700 space-y-1">
    <li>Cliquez sur une section dans le menu pour g&#233;rer son contenu.</li>
    <li>Utilisez les barres de recherche dans les tableaux pour trouver rapidement des entr&#233;es.</li>
    <li>Appuyez sur les boutons de tri pour organiser les donn&#233;es par date.</li>
    <li>Pensez &#224; mettre &#224; jour r&#233;guli&#232;rement les sections de la page d&#39;accueil.</li>
  </ul>
</div>
