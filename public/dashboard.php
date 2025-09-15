<?php
ob_start(); // Start output buffering
require_once '../includes/session.php';
require_once '../includes/config.php';

// Fetch group colors
$stmt = $pdo->query("SELECT * FROM group_elems LIMIT 1");
$group = $stmt->fetch();
$color_primary = $group['color_primary'] ?? '#FFFFFF';
$color_secondary = $group['color_secondary'] ?? '#FEF4EE';
$color_tertiary = $group['color_tertiary'] ?? '#F97316';
$homepage_main_color_text = $group['homepage_main_color_text'] ?? '#F97316';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Heroicons CDN -->
    <script src="https://unpkg.com/heroicons@1.0.6/dist/heroicons.min.js"></script>
</head>
<body class="flex bg-gray-100 text-gray-800">

    <?php include '../includes/dashboard/nav_bar.php'; ?>

    <main class="flex-1 p-6">
        <?php
        $page = $_GET['page'] ?? 'home';
        $allowed_pages = ['home','actualite','animaux','equipe','homepage_sections','users'];
        if(in_array($page, $allowed_pages)){
            include "../includes/dashboard/pages/$page.php";
        } else {
            echo "<h2 class='text-red-500 text-2xl'>Page not found</h2>";
        }
        ?>
    </main>

</body>
</html>

<?php
ob_end_flush(); // Send output buffer
?>