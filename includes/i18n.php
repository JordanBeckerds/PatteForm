<?php
/**
 * Minimal i18n helper.
 * Load this file once (after config.php). Then call t('key') anywhere in views.
 * Language files live in /lang/fr.php and /lang/en.php.
 */

$_pf_lang = $_ENV['APP_LANG'] ?? 'fr';
$_pf_file = dirname(__DIR__) . "/lang/{$_pf_lang}.php";
if (!file_exists($_pf_file)) {
    $_pf_file = dirname(__DIR__) . '/lang/fr.php';
}
$GLOBALS['_pf_strings'] = require $_pf_file;

/**
 * Translate a key. Replaces {var} placeholders with $vars values.
 * Output is HTML-escaped.
 */
function t(string $key, array $vars = []): string {
    $str = $GLOBALS['_pf_strings'][$key] ?? $key;
    foreach ($vars as $k => $v) {
        $str = str_replace('{' . $k . '}', htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'), $str);
    }
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

/**
 * Same as t() but allows raw HTML in the translated string.
 * Use only with strings you fully control (never with user data).
 */
function te(string $key, array $vars = []): string {
    $str = $GLOBALS['_pf_strings'][$key] ?? $key;
    foreach ($vars as $k => $v) {
        $str = str_replace('{' . $k . '}', (string)$v, $str);
    }
    return $str;
}
