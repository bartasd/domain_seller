<?php

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

return [
    'mail' => [
        'smtp_host' => $_ENV['SMTP_HOST'],
        'smtp_username' => $_ENV['SMTP_USERNAME'],
        'smtp_password' => $_ENV['SMTP_PASSWORD'],
        'smtp_port' => $_ENV['SMTP_PORT'],
        'sender' => [
            'email' => $_ENV['MAIL_FROM'],
            'name' => $_ENV['MAIL_NAME']
        ],
        'recipient' => [
            'email' => $_ENV['MAIL_TO'],
            'name' => $_ENV['MY_MAIL_NAME']
        ]
    ],
    'db' => [
        'host' => $_ENV['DB_HOST'],
        'name' => $_ENV['DB_NAME'],
        'user' => $_ENV['DB_USER'],
        'pass' => $_ENV['DB_PASS'],
    ],
    'site' => [
        'title' => $_ENV['SITE_TITLE'],
        'domain' => $_ENV['SITE_DOMAIN'],
        'description' => $_ENV['SITE_DESCRIPTION'],
    ],
    'server' => [
        // LATER MAKE IT UNIQUE TO YOUR SERVER, FOR EXAMPLE: 'https://yourdomain.com'
        'base_url' => $_SERVER['HTTP_HOST'] 
    ]
];