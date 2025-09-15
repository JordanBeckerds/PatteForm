<?php
if(isset($_POST['add_actualite'])){
    $stmt = $pdo->prepare("INSERT INTO actualite (titre, description, img, date_publication) VALUES (?, ?, ?, ?)");
    $stmt->execute([$_POST['titre'], $_POST['description'], $_POST['img'], $_POST['date_publication']]);
    header("Location: dashboard.php?page=actualite");
}

// Delete
if(isset($_GET['delete'])){
    $stmt = $pdo->prepare("DELETE FROM actualite WHERE id=?");
    $stmt->execute([$_GET['delete']]);
    header("Location: dashboard.php?page=actualite");
}

// Edit (optional, prefill form)
if(isset($_GET['edit'])){
    $stmt = $pdo->prepare("SELECT * FROM actualite WHERE id=?");
    $stmt->execute([$_GET['edit']]);
    $edit_data = $stmt->fetch();
}

// Update
if(isset($_POST['update_actualite'])){
    $stmt = $pdo->prepare("UPDATE actualite SET titre=?, description=?, img=?, date_publication=? WHERE id=?");
    $stmt->execute([$_POST['titre'], $_POST['description'], $_POST['img'], $_POST['date_publication'], $_POST['id']]);
    header("Location: dashboard.php?page=actualite");
}
