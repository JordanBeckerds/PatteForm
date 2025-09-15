<?php
// Fetch group_elem data from DB (assuming $pdo is your PDO connection)
$stmt = $pdo->query("SELECT * FROM group_elems LIMIT 1");
$group = $stmt->fetch(PDO::FETCH_ASSOC);

// Assign group variables with fallbacks
$name = $group['group_name'] ?? 'Nom non renseigné';
$adress = $group['adress'] ?? '14 avenue du Doyen Robert Poplawski, 64000 Pau, France';
$telephone = $group['telephone'] ?? '06 00 00 00 00';

$logo = $group['logo'] ?? null;

$color_primary = $group['color_primary'] ?? '#FFFFFF';
$color_secondary = $group['color_secondary'] ?? '#FEF4EE';
$color_tertiary = $group['color_tertiary'] ?? '#F97316';
$color_title = $group['color_title'] ?? '#FEF4EE';
$homepage_main_color_text = $group['homepage_main_color_text'] ?? '#F97316';

$date_creation = $group['date_creation'] ?? null;
$year = $date_creation ? (new DateTime($date_creation))->format('Y') : 'Année inconnue';

$horaires_ouvert = $group['horaires_ouvert'] ?? '1700000000';

$social_facebook = $group['social_facebook'] ?? '#';
$social_twitter = $group['social_twitter'] ?? '#';
$social_instagram = $group['social_instagram'] ?? '#';

$donation_link = $group['donation_link'] ?? null;
$donation_link_bool = $group['donation_link_bool'] ?? false;

// Count adoptable and adopted animals
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

// === Horaires Parsing ===
function joursTexte($code) {
    $map = [
        '1' => 'Lundi',
        '2' => 'Mardi',
        '3' => 'Mercredi',
        '4' => 'Jeudi',
        '5' => 'Vendredi',
        '6' => 'Samedi',
        '7' => 'Dimanche',
    ];

    $jours = array_unique(str_split($code));
    $noms = [];

    foreach ($jours as $j) {
        $noms[] = $map[$j] ?? "Jour inconnu ($j)";
    }

    if (count($noms) > 1) {
        $last = array_pop($noms);
        return implode(', ', $noms) . ' et ' . $last;
    } else {
        return $noms[0] ?? "Jours inconnus ($code)";
    }
}

function formatPlageHoraire($plage) {
    $h_deb = substr($plage, 0, 2);
    $m_deb = substr($plage, 2, 2);
    $h_fin = substr($plage, 4, 2);
    $m_fin = substr($plage, 6, 2);
    return "{$h_deb}h{$m_deb} - {$h_fin}h{$m_fin}";
}

function parseHorairesOuvert($str) {
    $result = [];
    $groupes = explode('6969', $str);

    foreach ($groupes as $groupe) {
        if (strlen($groupe) < 2) continue;

        $code_jours = substr($groupe, 0, 2);
        $jours = joursTexte($code_jours);

        $plages_str = substr($groupe, 2);
        $plages = str_split($plages_str, 8);

        $horaires = [];
        foreach ($plages as $plage) {
            if (strlen($plage) === 8) {
                $horaires[] = formatPlageHoraire($plage);
            }
        }

        $result[] = [
            'jours' => $jours,
            'horaires' => $horaires,
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
  <link rel="icon" type="image/x-icon" href="<?= htmlspecialchars($logo) ?>">
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      const btn = document.getElementById("mobile-menu-button");
      const icon = document.getElementById("burger-icon");
      const menu = document.getElementById("mobile-menu");

      btn.addEventListener("click", () => {
        menu.classList.toggle("max-h-0");
        menu.classList.toggle("max-h-[600px]");
        icon.classList.toggle("rotate-90");
      });
    });
  </script>
  <style>
    /* Use PHP to inject your colors */
    body {
      background-color: <?= htmlspecialchars($color_primary) ?>;
      color: #374151; /* Keep text color default gray, not changed */
      font-family: sans-serif;
    }

    header {
      background-color: <?= htmlspecialchars($color_primary) ?>;
      border-bottom-color: #e5e7eb; /* Tailwind gray-200 */
    }

    .nav-link {
      position: relative;
      transition: color 0.3s;
      color: black; /* default text gray */
    }
    .nav-link:hover {
      color: <?= htmlspecialchars($color_tertiary) ?>;
    }
    .nav-link::after {
      content: "";
      position: absolute;
      left: 0;
      bottom: -4px;
      height: 2px;
      width: 100%;
      background: <?= htmlspecialchars($color_tertiary) ?>;
      transform: scaleX(0);
      transform-origin: left;
      transition: transform 0.3s ease-in-out;
    }
    .nav-link:hover::after {
      transform: scaleX(1);
    }

    .mobile-link {
      display: block;
      padding: 1rem 1.5rem;
      border-bottom: 1px solid black;
      color: black;
      transition: all 0.3s;
      position: relative;
      background-color: <?= htmlspecialchars($color_primary) ?>;
    }
    .mobile-link:hover {
      background: <?= htmlspecialchars($color_secondary) ?>;
      color: <?= htmlspecialchars($color_tertiary) ?>;
    }
    .mobile-link::after {
      content: "";
      position: absolute;
      bottom: 0.5rem;
      left: 1.5rem;
      height: 2px;
      width: 64px;
      background: <?= htmlspecialchars($color_tertiary) ?>;
      transform: scaleX(0);
      transform-origin: left;
      transition: transform 0.3s ease-in-out;
    }
    .mobile-link:hover::after {
      transform: scaleX(1);
    }

    #mobile-menu {
      background-color: <?= htmlspecialchars($color_primary) ?>;
      border-top-color: #e5e7eb;
    }

    #mobile-menu-button {
      border-color: <?= htmlspecialchars($color_tertiary) ?>;
      color: <?= htmlspecialchars($color_tertiary) ?>;
    }
    #mobile-menu-button:hover {
      background-color: <?= htmlspecialchars(opacityColor($color_tertiary, 0.1)) ?>;
    }
  </style>
</head>
<body>

<header class="shadow-md sticky top-0 z-50">
  <div class="container mx-auto flex justify-between items-center px-6 py-6 md:py-8">
    <a href="../public/index.php" class="flex items-center space-x-2">
      <img src="<?= htmlspecialchars($logo) ?>" alt="Logo" class="h-24 w-auto object-contain" />
    </a>

    <!-- Desktop Menu -->
    <nav class="hidden md:flex space-x-8 text-lg font-medium items-center">
      <a href="../public/index.php" class="nav-link">Accueil</a>
      <a href="../public/adoption.php" class="nav-link">Adoption</a>
      <a href="../public/actualites.php" class="nav-link">Actualités</a>
      <a href="../public/contact.php" class="nav-link">Contact</a>
      <a href="../public/donation.php" class="nav-link">Donation</a>
      <a href="../public/about.php" class="nav-link">About</a>
    </nav>

    <!-- Mobile Hamburger -->
    <button
      id="mobile-menu-button"
      class="md:hidden p-2 rounded-md hover:bg-opacity-10 focus:outline-none focus:ring-2 transition"
    >
      <svg
        id="burger-icon"
        class="w-6 h-6 transition-transform duration-300 ease-in-out"
        fill="none"
        stroke="currentColor"
        stroke-width="2"
        viewBox="0 0 24 24"
        stroke-linecap="round"
        stroke-linejoin="round"
      >
        <path d="M4 6h16M4 12h16M4 18h16" />
      </svg>
    </button>
  </div>

  <!-- Mobile Menu -->
  <nav
    id="mobile-menu"
    class="overflow-hidden transition-all duration-300 max-h-0 md:hidden border-t shadow-md"
  >
    <a href="../public/index.php" class="mobile-link">Accueil</a>
    <a href="../public/adoption.php" class="mobile-link">Adoption</a>
    <a href="../public/actualites.php" class="mobile-link">Actualités</a>
    <a href="../public/contact.php" class="mobile-link">Contact</a>
    <a href="../public/donation.php" class="mobile-link">Donation</a>
    <a href="../public/donation.php" class="mobile-link">About</a>
    </nav>
</header>

<?php
// PHP helper to apply opacity to a hex color
function opacityColor($hex, $opacity) {
    $hex = str_replace('#', '', $hex);
    if(strlen($hex) == 3){
        $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
    }
    $r = hexdec(substr($hex, 0, 2));
    $g = hexdec(substr($hex, 2, 2));
    $b = hexdec(substr($hex, 4, 2));
    return "rgba($r, $g, $b, $opacity)";
}
?>

</body>
</html>