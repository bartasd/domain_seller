<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// GET STATE
$state = require __DIR__ . '/state.php';

// GET CONFIG
$config = require __DIR__ . '/../config/config.php';


// GET LANGUAGE FILE
$lang = require __DIR__ . '/language.php';


// GET VALIDATION FUNCTIONS
require_once __DIR__ . '/validations.php';


// GET STORE FUNCTIONS
require_once __DIR__ . '/store.php';


// GET SEND EMAIL FUNCTIONS
require_once __DIR__ . '/send_email.php';


// GET RATE LIMITING FUNCTIONS
require_once __DIR__ . '/rate_limiting.php';


// GET REDIRECT FUNCTIONS
require_once __DIR__ . '/redirect.php';


// EMAIL SENT
require_once __DIR__ . '/email_sent.php';


$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$message = $_POST['message'] ?? '';

$ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';

$domain = $config['site']['url'] ?? 'unknown';


// Store variables in session for later use
// if validation fails.

$_SESSION['old'] = [
    'name' => $name,
    'email' => $email,
    'message' => $message
];


// CHECK RATE LIMITING

if (!check_submit_limit($ip, $domain, $config)) {

    $_SESSION['errors'] = [
        $lang['rate_limit_exceeded']
    ];

    unset($_SESSION['old']);

    redirect_home($config);
}


// VALIDATE FORM

$errors = validate_form(
    $name,
    $email,
    $message,
    $lang
);


if (!empty($errors)) {

    $_SESSION['errors'] = $errors;

    $_SESSION['old'] = [
        'name' => $name,
        'email' => $email,
        'message' => $message
    ];

}
else {

    $_SESSION['success'] = 'Message sent successfully.';

    unset($_SESSION['old']);


    // MESSAGE IS STORED IN DATABASE
    // RETURNS MESSAGE ID

    $id = store(
        $name,
        $email,
        $message,
        $ip,
        $domain,
        $config
    );

    // SEND EMAIL

    if ($id !== null) {

        // CHECK EMAIL RATE LIMMITS
        $let_send = check_email_limit($config);

        if ($let_send && send_email($id, $config)) {
            email_sent($id, $config);
            // WHAT HAPPENTS IF EMAIL IS NOT ATTRIBUTED AS SENT?

        }
        else{
            // EMAIL NOT SENT
            // PREPARE WORKER FOR MESSAGE ID = $id
            invert_state($state);
        }

    }
    else {

        // DATABASE STORAGE FAILED
        // WHAT HAPPENS IF MESSAGE IS NOT STORED IN DATABASE?

    }
}


redirect_home($config);