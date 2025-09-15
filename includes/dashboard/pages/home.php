<?php
// Statistiques rapides
$tables = [
    'Actualités' => 'actualite',
    'Animaux' => 'animaux_a_adopter',
    'Sections Accueil' => 'homepage_sections',
    'Équipe' => 'equipe',
    'Utilisateurs' => 'users'
];

$stats = [];
foreach($tables as $label => $table) {
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM $table");
    $stats[$label] = $stmt->fetch()['total'] ?? 0;
}

// Actualités récentes
$recent_act = $pdo->query("SELECT * FROM actualite ORDER BY date_publication DESC LIMIT 3")->fetchAll();

// Animaux récents
$recent_animaux = $pdo->query("SELECT * FROM animaux_a_adopter ORDER BY date_arriver DESC LIMIT 3")->fetchAll();
?>

<h1 class="text-3xl font-bold mb-6 text-[<?=$color_tertiary?>]">Bienvenue, Admin !</h1>

<p class="mb-6 text-gray-700">
    Voici un aperçu rapide de votre dashboard SPA. Vous pouvez gérer les actualités, les animaux, les sections de la page d’accueil, les membres de l’équipe et les utilisateurs directement depuis cette interface.
</p>

<!-- Statistiques rapides -->
<div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-6 mb-8">
    <?php foreach($stats as $label => $count): ?>
        <a href="dashboard.php?page=<?= strtolower(str_replace(' ', '_', $label)) ?>" 
           class="bg-[<?=$color_primary?>] p-6 rounded shadow hover:shadow-lg transition flex flex-col items-center justify-center">
            <div class="text-4xl font-bold text-[<?=$color_tertiary?>]"><?= $count ?></div>
            <div class="mt-2 text-gray-700 font-semibold"><?= $label ?></div>
        </a>
    <?php endforeach; ?>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Actualités récentes -->
    <div class="bg-white p-6 rounded shadow">
        <h2 class="text-xl font-bold mb-4 text-[<?=$color_tertiary?>]">Actualités récentes</h2>
        <ul class="space-y-2">
            <?php foreach($recent_act as $act): ?>
                <li class="border-b py-2 flex justify-between items-center">
                    <span><?= htmlspecialchars($act['titre']) ?></span>
                    <span class="text-gray-500 text-sm"><?= $act['date_publication'] ?></span>
                </li>
            <?php endforeach; ?>
            <?php if(empty($recent_act)) echo "<li class='text-gray-400'>Aucune actualité pour le moment.</li>"; ?>
        </ul>
    </div>

    <!-- Animaux récents -->
    <div class="bg-white p-6 rounded shadow">
        <h2 class="text-xl font-bold mb-4 text-[<?=$color_tertiary?>]">Arrivées récentes d'animaux</h2>
        <ul class="space-y-2">
            <?php foreach($recent_animaux as $dog): ?>
                <li class="border-b py-2 flex justify-between items-center">
                    <span><?= htmlspecialchars($dog['nom']) ?> (<?= $dog['espece'] ?>)</span>
                    <span class="text-gray-500 text-sm"><?= $dog['date_arriver'] ?></span>
                </li>
            <?php endforeach; ?>
            <?php if(empty($recent_animaux)) echo "<li class='text-gray-400'>Aucune arrivée récente.</li>"; ?>
        </ul>
    </div>
</div>

<!-- Conseils pour l'admin -->
<div class="mt-8 bg-[<?=$color_secondary?>] p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-2 text-[<?=$color_tertiary?>]">Conseils rapides</h2>
    <ul class="list-disc pl-5 text-gray-700 space-y-1">
        <li>Cliquez sur une section dans le menu pour gérer son contenu.</li>
        <li>Utilisez les barres de recherche dans les tableaux pour trouver rapidement des entrées.</li>
        <li>Appuyez sur les boutons de tri pour organiser les données par date.</li>
        <li>Pensez à mettre à jour régulièrement les sections de la page d’accueil pour vos visiteurs.</li>
    </ul>
</div>