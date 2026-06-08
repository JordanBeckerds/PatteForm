<?php
ob_start();
require_once '../includes/session.php';
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Handle favorite toggle BEFORE any output
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['favorite_id'])) {
    $fav_id = (int)$_POST['favorite_id'];
    if (!isset($_SESSION['favorites'])) {
        $_SESSION['favorites'] = [];
    }
    if (in_array($fav_id, $_SESSION['favorites'], true)) {
        $_SESSION['favorites'] = array_values(array_diff($_SESSION['favorites'], [$fav_id]));
    } else {
        $_SESSION['favorites'][] = $fav_id;
    }
    header('Location: adoption.php?' . $_SERVER['QUERY_STRING']);
    exit;
}
?>
<?php include '../includes/header.php'; ?>
<?php
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    include '../includes/adoption/animal_display.php';
} else {
    include '../includes/adoption/adoption_info.php';
    include '../includes/adoption/animaux_search.php';
}
?>
<?php include '../includes/donate_btn.php'; ?>
<?php include '../includes/footer.php'; ?>
<?php ob_end_flush(); ?>
