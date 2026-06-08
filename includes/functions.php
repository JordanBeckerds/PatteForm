<?php
if (!function_exists('h')) {
    function h(string $str): string {
        return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
    }
}

function redirect(string $url): never {
    header('Location: ' . $url);
    exit;
}

function flash(string $key, string $message): void {
    if (session_status() === PHP_SESSION_NONE) session_start();
    $_SESSION[$key] = $message;
}

function get_flash(string $key): ?string {
    if (!isset($_SESSION[$key])) return null;
    $msg = $_SESSION[$key];
    unset($_SESSION[$key]);
    return $msg;
}

function format_date(string $date, string $fmt = 'd/m/Y'): string {
    return (new DateTime($date))->format($fmt);
}

function format_time(string $time): string {
    return substr($time, 0, 5);
}
