<?php
ob_start();
require_once '../includes/session.php'; // session_start()
require_once '../includes/config.php';

// Handle favorite toggle BEFORE any output
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['favorite_id'])) {
    $id = (int)$_POST['favorite_id'];
    if (!isset($_SESSION['favorites'])) {
        $_SESSION['favorites'] = [];
    }
    if (in_array($id, $_SESSION['favorites'])) {
        // Remove from favorites
        $_SESSION['favorites'] = array_diff($_SESSION['favorites'], [$id]);
    } else {
        // Add to favorites
        $_SESSION['favorites'][] = $id;
    }
    header("Location: adoption.php?" . $_SERVER['QUERY_STRING']);
    exit;
}
?>

<?php include '../includes/header.php'; ?>

<?php
// Show single animal detail if id is set and numeric, else show search/list
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    include '../includes/adoption/animal_display.php';
} else {
    include '../includes/adoption/animaux_search.php';
}
?>

<?php include '../includes/donate_btn.php'; ?>

<?php include '../includes/footer.php'; ?>