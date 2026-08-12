<?php

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
    array $supportedLanguages
): string {

    if (!in_array($language, $supportedLanguages, true)) {
        $language = $supportedLanguages[0];
    }

    $_SESSION['language'] = $language;

    header('Location: ../index.php');
    die;
}

// USE IT TO SWITCH LANGUAGE WHEN USER SELECTS A LANGUAGE

$postedLanguage = $_POST['language'] ?? null;
unset($_POST['language']);

if ($postedLanguage !== null) {
    var_dump($postedLanguage);
    set_language($postedLanguage, $supportedLanguages);
}

//////////////////////////////////////////////////////////

$language = $_SESSION['language'] ?? $defaultLanguage;

return require __DIR__ . "/../config/languages/$language.php"; // ACTUAL LANGUAGE FILE IS RETURNED HERE