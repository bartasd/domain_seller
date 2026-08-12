<?php
    session_start();
    // GET VALIDATION FUNCTIONS
    require './validations.php';

    // GET STORE FUNCTIONS
    require './store.php';  

    // GET SEND EMAIL FUNCTIONS
    require './send_email.php';

    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $message = $_POST['message'] ?? '';

    // store variables in session for later use if needed after invalidation
    $_SESSION['old'] = [
        'name' => $name,
        'email' => $email,
        'message' => $message
    ];

    // VALIDATE FORM
    $errors = validate_form($name, $email, $message);

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;

        $_SESSION['old'] = [
            'name' => $name,
            'email' => $email,
            'message' => $message
        ];
    }
    else{
        $_SESSION['success'] = 'Message sent successfully.';
        
        // SEND EMAIL TO NOTIFY ME ABOUT NEW MESSAGE
        send_email($name, $email, $message);
    }

    header('Location: ../index.php');
    die;
?>