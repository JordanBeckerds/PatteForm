<?php
// Included only from dashboard context (auth already checked by public/dashboard.php)

$edit_data = null;

if (isset($_POST['add_actualite'])) {
    $stmt = $pdo->prepare(
        'INSERT INTO actualite (titre, description, img, date_publication) VALUES (?, ?, ?, ?)'
    );
    $stmt->execute([
        $_POST['titre'] ?? '',
        $_POST['description'] ?? '',
        $_POST['img'] ?? '',
        $_POST['date_publication'] ?? date('Y-m-d'),
    ]);
    header('Location: dashboard.php?page=actualite');
    exit;
}

if (isset($_POST['update_actualite'])) {
    $stmt = $pdo->prepare(
        'UPDATE actualite SET titre=?, description=?, img=?, date_publication=? WHERE id=?'
    );
    $stmt->execute([
        $_POST['titre'] ?? '',
        $_POST['description'] ?? '',
        $_POST['img'] ?? '',
        $_POST['date_publication'] ?? '',
        (int)($_POST['id'] ?? 0),
    ]);
    header('Location: dashboard.php?page=actualite');
    exit;
}

if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare('DELETE FROM actualite WHERE id=?');
    $stmt->execute([(int)$_GET['delete']]);
    header('Location: dashboard.php?page=actualite');
    exit;
}

if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare('SELECT * FROM actualite WHERE id=?');
    $stmt->execute([(int)$_GET['edit']]);
    $edit_data = $stmt->fetch() ?: null;
}
