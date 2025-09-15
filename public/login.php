<?php
ob_start(); // Start output buffering
require_once '../includes/session.php';
require_once '../includes/config.php';
?>

<?php include '../includes/header.php'; ?>

<?php include '../includes/login/main.php'; ?>

<?php include '../includes/footer.php'; ?>

<?php
ob_end_flush(); // Send output buffer
?>