<?php
require_once '../includes/session.php';
require_once '../includes/config.php';
?>

<?php 
include '../includes/header.php'; 

// Check if ID is set in URL, then show single actualite
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    include '../includes/actualites/actualites_diplay.php';
} else {
    // Otherwise show search/list page
    include '../includes/actualites/actualites_search.php';
}

include '../includes/donate_btn.php';

include '../includes/footer.php'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <img ti src="" alt="">
</body>
</html>