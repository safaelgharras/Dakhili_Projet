<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$translations = require __DIR__ . '/translations.php';

// Set language
if (isset($_GET['lang'])) {
    $lang = $_GET['lang'];
    if (array_key_exists($lang, $translations)) {
        $_SESSION['lang'] = $lang;
        setcookie('lang', $lang, time() + (86400 * 30), "/"); // 30 days
    }
}

$currentLang = $_SESSION['lang'] ?? $_COOKIE['lang'] ?? 'fr';

function __($key) {
    global $translations, $currentLang;
    return $translations[$currentLang][$key] ?? $key;
}

function getLang() {
    global $currentLang;
    return $currentLang;
}

function isRTL() {
    return getLang() === 'ar';
}

function getLocalizedDbField($row, $field) {
    global $currentLang;
    if ($currentLang === 'ar' && !empty($row[$field . '_ar'])) {
        return $row[$field . '_ar'];
    }
    if ($currentLang === 'en' && !empty($row[$field . '_en'])) {
        return $row[$field . '_en'];
    }
    return $row[$field] ?? '';
}
?>
