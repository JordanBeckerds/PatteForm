<?php
// Must be included after config.php (which sets up $pdo)

function opacityColor(string $hex, float $opacity): string {
    $hex = ltrim($hex, '#');
    if (strlen($hex) === 3) {
        $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
    }
    $r = hexdec(substr($hex, 0, 2));
    $g = hexdec(substr($hex, 2, 2));
    $b = hexdec(substr($hex, 4, 2));
    return "rgba($r,$g,$b,$opacity)";
}

// Fetch shelter branding from DB
$stmt  = $pdo->query('SELECT * FROM group_elems LIMIT 1');
$group = $stmt->fetch(PDO::FETCH_ASSOC);

$name      = $group['group_name'] ?? 'Mon refuge';
$adress    = $group['adress']     ?? '';
$telephone = $group['telephone']  ?? '';
$logo      = $group['logo']       ?? null;

$color_primary   = $group['color_primary']   ?? '#FFFFFF';
$color_secondary = $group['color_secondary'] ?? '#FEF4EE';
$color_tertiary  = $group['color_tertiary']  ?? '#F97316';
$color_title     = $group['color_title']     ?? '#1e293b';
$homepage_main_color_text = $group['homepage_main_color_text'] ?? $color_tertiary;

$date_creation = $group['date_creation'] ?? null;
$year          = $date_creation ? (new DateTime($date_creation))->format('Y') : date('Y');

$horaires_ouvert   = $group['horaires_ouvert']   ?? '';
$social_facebook   = $group['social_facebook']   ?? '#';
$social_twitter    = $group['social_twitter']    ?? '#';
$social_instagram  = $group['social_instagram']  ?? '#';
$donation_link_bool = $group['donation_link_bool'] ?? false;
$donation_link     = $group['donation_link']     ?? null;

try {
    $totalToAdopt  = $pdo->query('SELECT COUNT(*) FROM animaux_a_adopter')->fetchColumn();
    $totalAdopted  = $pdo->query('SELECT COUNT(*) FROM animaux_adopter')->fetchColumn();
} catch (PDOException) {
    $totalToAdopt = $totalAdopted = 0;
}

// --- Opening hours parser ---
function joursTexte(string $code): string {
    $map  = ['1'=>'Lundi','2'=>'Mardi','3'=>'Mercredi','4'=>'Jeudi','5'=>'Vendredi','6'=>'Samedi','7'=>'Dimanche'];
    $noms = array_unique(array_map(fn($c) => $map[$c] ?? "Jour $c", str_split($code)));
    if (count($noms) > 1) {
        $last = array_pop($noms);
        return implode(', ', $noms) . ' et ' . $last;
    }
    return $noms[0] ?? $code;
}

function formatPlageHoraire(string $p): string {
    return substr($p,0,2).'h'.substr($p,2,2).' - '.substr($p,4,2).'h'.substr($p,6,2);
}

function parseHorairesOuvert(string $str): array {
    $result = [];
    foreach (explode('6969', $str) as $groupe) {
        if (strlen($groupe) < 2) continue;
        $result[] = [
            'jours'    => joursTexte(substr($groupe, 0, 2)),
            'horaires' => array_map('formatPlageHoraire',
                array_filter(str_split(substr($groupe, 2), 8), fn($p) => strlen($p) === 8)
            ),
        ];
    }
    return $result;
}

$parsedHoraires = parseHorairesOuvert($horaires_ouvert);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?= htmlspecialchars($name) ?></title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body    { background-color: <?= htmlspecialchars($color_primary) ?>; font-family: sans-serif; }
    header  { background-color: <?= htmlspecialchars($color_primary) ?>; }
    .nav-link { position:relative; transition:color .3s; color:#111; }
    .nav-link:hover { color: <?= htmlspecialchars($color_tertiary) ?>; }
    .nav-link::after {
      content:""; position:absolute; left:0; bottom:-4px; height:2px; width:100%;
      background: <?= htmlspecialchars($color_tertiary) ?>;
      transform:scaleX(0); transform-origin:left; transition:transform .3s ease;
    }
    .nav-link:hover::after { transform:scaleX(1); }
    .mobile-link {
      display:block; padding:1rem 1.5rem; border-bottom:1px solid #e5e7eb;
      color:#111; transition:all .3s; background-color: <?= htmlspecialchars($color_primary) ?>;
    }
    .mobile-link:hover { background: <?= htmlspecialchars($color_secondary) ?>; color: <?= htmlspecialchars($color_tertiary) ?>; }
    #mobile-menu { background-color: <?= htmlspecialchars($color_primary) ?>; }
    #mobile-menu-button { border-color: <?= htmlspecialchars($color_tertiary) ?>; color: <?= htmlspecialchars($color_tertiary) ?>; }
    #mobile-menu-button:hover { background-color: <?= opacityColor($color_tertiary, 0.1) ?>; }
  </style>
</head>
<body>

<header class="shadow-md sticky top-0 z-50 border-b border-gray-200">
  <div class="container mx-auto flex justify-between items-center px-6 py-4">
    <a href="../public/index.php" class="flex items-center">
      <?php if ($logo): ?>
        <img src="<?= htmlspecialchars($logo) ?>" alt="<?= htmlspecialchars($name) ?>" class="h-16 w-auto object-contain" />
      <?php else: ?>
        <span class="text-xl font-bold" style="color:<?= htmlspecialchars($color_tertiary) ?>"><?= htmlspecialchars($name) ?></span>
      <?php endif; ?>
    </a>

    <!-- Desktop nav -->
    <nav class="hidden md:flex space-x-8 text-base font-medium items-center">
      <a href="../public/index.php"       class="nav-link">Accueil</a>
      <a href="../public/adoption.php"    class="nav-link">Adoption</a>
      <a href="../public/actualites.php"  class="nav-link">Actualités</a>
      <a href="../public/contact.php"     class="nav-link">Contact</a>
      <a href="../public/donation.php"    class="nav-link">Donation</a>
      <a href="../public/about.php"       class="nav-link">À propos</a>
    </nav>

    <!-- Mobile burger -->
    <button id="mobile-menu-button" class="md:hidden p-2 rounded-md border focus:outline-none"
      aria-label="Menu" aria-expanded="false" aria-controls="mobile-menu">
      <svg id="burger-icon" class="w-6 h-6 transition-transform duration-300" fill="none" stroke="currentColor"
        stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
        <path d="M4 6h16M4 12h16M4 18h16" />
      </svg>
    </button>
  </div>

  <!-- Mobile menu -->
  <nav id="mobile-menu" class="overflow-hidden transition-all duration-300 max-h-0 md:hidden border-t border-gray-200"
    aria-label="Mobile navigation">
    <a href="../public/index.php"      class="mobile-link">Accueil</a>
    <a href="../public/adoption.php"   class="mobile-link">Adoption</a>
    <a href="../public/actualites.php" class="mobile-link">Actualités</a>
    <a href="../public/contact.php"    class="mobile-link">Contact</a>
    <a href="../public/donation.php"   class="mobile-link">Donation</a>
    <a href="../public/about.php"      class="mobile-link">À propos</a>
  </nav>
</header>

<script>
  (function () {
    const btn  = document.getElementById('mobile-menu-button');
    const icon = document.getElementById('burger-icon');
    const menu = document.getElementById('mobile-menu');
    btn.addEventListener('click', () => {
      const open = menu.classList.toggle('max-h-0');
      menu.classList.toggle('max-h-[600px]');
      icon.classList.toggle('rotate-90');
      btn.setAttribute('aria-expanded', String(!open));
    });
  })();
</script>
