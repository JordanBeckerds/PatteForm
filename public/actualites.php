<?php
require_once '../includes/session.php';
require_once '../includes/config.php';
require_once '../includes/functions.php';

include '../includes/header.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    include '../includes/actualites/actualites_diplay.php';
} else {
    include '../includes/actualites/actualites_search.php';
}

include '../includes/donate_btn.php';
include '../includes/footer.php';
