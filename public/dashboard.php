<?php
ob_start();
require_once '../includes/session.php';
require_once '../includes/config.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

// Only logged-in users can access the dashboard; admins get full access
require_login('../public/login.php');

// Fetch group colors
$stmt = $pdo->query('SELECT * FROM group_elems LIMIT 1');
$group = $stmt->fetch();
$color_primary            = $group['color_primary']            ?? '#FFFFFF';
$color_secondary          = $group['color_secondary']          ?? '#FEF4EE';
$color_tertiary           = $group['color_tertiary']           ?? '#F97316';
$homepage_main_color_text = $group['homepage_main_color_text'] ?? '#F97316';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard — Patteform</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex bg-gray-100 text-gray-800">

    <?php include '../includes/dashboard/nav_bar.php'; ?>

    <main class="flex-1 p-6">
        <?php
        $page = $_GET['page'] ?? 'home';
        $allowed_pages = ['home', 'actualite', 'animaux', 'equipe', 'homepage_sections', 'users'];
        if (in_array($page, $allowed_pages, true)) {
            include "../includes/dashboard/pages/{$page}.php";
        } else {
            echo "<h2 class='text-red-500 text-2xl'>Page introuvable</h2>";
        }
        ?>
    </main>

</body>
</html>
<?php ob_end_flush(); ?>
