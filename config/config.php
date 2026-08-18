<?php

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

return [
    'mail' => [
        'smtp_host' => $_ENV['SMTP_HOST'],
        'smtp_username' => $_ENV['SMTP_MAIL'],
        'smtp_password' => $_ENV['SMTP_PASSWORD'],
        'smtp_port' => $_ENV['SMTP_PORT'],
        'sender' => [
            'email' => $_ENV['SMTP_MAIL'],
            'name' => $_SERVER['HTTP_HOST']
        ],
        'recipient' => [
            'email' => $_ENV['MY_MAIL'],
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
        'url' => $_SERVER['HTTP_HOST'],
        'local_url' => $_ENV['LOCAL_PROJECT_NAME'],
    ],
    'limits' => [
        'ip_last_minute' => $_ENV['IP_LIMIT_MINUTE'],
        'ip_last_10_minutes' => $_ENV['IP_LIMIT_10_MINUTES'],
        'ip_last_hour' => $_ENV['IP_LIMIT_HOUR'],
        'domain_last_10_minutes' => $_ENV['DOMAIN_LIMIT_10_MINUTES'],
        'domain_last_hour' => $_ENV['DOMAIN_LIMIT_HOUR'],
        'global_last_hour' => $_ENV['GLOBAL_LIMIT_HOUR'],
        'email_limit_daily' => $_ENV['EMAIL_LIMIT_DAILY']
    ]
];