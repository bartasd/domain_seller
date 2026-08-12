<?php

function valid_name(string $name): bool
{
    $name = trim($name);

    return $name !== ''
        && mb_strlen($name) <= 100;
}

function valid_email(string $email): bool
{
    $email = trim($email);

    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function valid_message(string $message): bool
{
    $message = trim($message);

    return $message !== ''
        && mb_strlen($message) <= 2000;
}

function validate_form(
    string $name,
    string $email,
    string $message,
    array $lang
): array {

    $errors = [];

    if (!valid_name($name)) {
        $errors[] = $lang['invalid_name'];
    }

    if (!valid_email($email)) {
        $errors[] = $lang['invalid_email'];
    }

    if (!valid_message($message)) {
        $errors[] = $lang['invalid_message'];
    }

    return $errors;
}