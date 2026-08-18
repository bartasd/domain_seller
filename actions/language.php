<?php

$config = require __DIR__ . '/../config/config.php';

// GET REDIRECT FUNCTIONS
require_once __DIR__ . '/redirect.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$supportedLanguages = [
    'lt',
    'en',
    'ru',
    'es'
];

$defaultLanguage = $supportedLanguages[0];

function set_language(
    string $language,
    array $supportedLanguages,
    array $config
): string {

    if (!in_array($language, $supportedLanguages, true)) {
        $language = $supportedLanguages[0];
    }

    $_SESSION['language'] = $language;

    redirect_home($config);
}

// USE IT TO SWITCH LANGUAGE WHEN USER SELECTS A LANGUAGE

$postedLanguage = $_POST['language'] ?? null;
unset($_POST['language']);

if ($postedLanguage !== null) {
    set_language($postedLanguage, $supportedLanguages, $config);
}

$language = $_SESSION['language'] ?? $defaultLanguage;

return require __DIR__ . "/../config/languages/$language.php"; // ACTUAL LANGUAGE FILE IS RETURNED HERE