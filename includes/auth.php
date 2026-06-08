<?php
function require_login(string $redirect = 'login.php'): void {
    if (session_status() === PHP_SESSION_NONE) session_start();
    if (empty($_SESSION['user_id'])) {
        header('Location: ' . $redirect);
        exit;
    }
}

function require_admin(string $redirect = 'dashboard.php'): void {
    require_login();
    if (($_SESSION['user_role'] ?? '') !== 'admin') {
        header('Location: ' . $redirect);
        exit;
    }
}

function current_user_id(): int {
    return (int)($_SESSION['user_id'] ?? 0);
}

function current_user_role(): string {
    return $_SESSION['user_role'] ?? '';
}

function is_admin(): bool {
    return current_user_role() === 'admin';
}

function is_staff(): bool {
    return current_user_role() === 'staff';
}
