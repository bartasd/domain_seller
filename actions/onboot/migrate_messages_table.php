<?php

$config = require_once __DIR__ . '/../../config/config.php';


function get_database(array $config): PDO
{
    $db = $config['db'];

    $pdo = new PDO(
        "mysql:host={$db['host']};port={$db['port']};dbname={$db['name']};charset=utf8mb4",
        $db['user'],
        $db['pass']
    );

    $pdo->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );

    return $pdo;
}


function migrateMessages(array $config): void
{
    $pdo = get_database($config);

    $sql = "
        CREATE TABLE IF NOT EXISTS messages (

            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

            name VARCHAR(100) NOT NULL,

            email VARCHAR(255) NOT NULL,

            message TEXT NOT NULL,

            ip VARCHAR(45) NOT NULL,

            domain VARCHAR(255) NOT NULL,

            created_at DATETIME NOT NULL
                DEFAULT CURRENT_TIMESTAMP,

            email_sent TINYINT(1) NOT NULL
                DEFAULT 0,

            email_sent_at DATETIME NULL,

            email_send_retries INT UNSIGNED NOT NULL
                DEFAULT 0,

            INDEX idx_ip_created_at (
                ip,
                created_at
            ),

            INDEX idx_domain_created_at (
                domain,
                created_at
            )
        )
    ";

    $pdo->exec($sql);
}


function migrateIPretries(array $config): void
{
    $pdo = get_database($config);

    $sql = "
        CREATE TABLE IF NOT EXISTS ip_retries (

            ip VARCHAR(45) PRIMARY KEY,

            retries INT UNSIGNED NOT NULL
                DEFAULT 0
        )
    ";

    $pdo->exec($sql);
}


function migrateDomains(array $config): void
{
    $pdo = get_database($config);

    $sql = "
        CREATE TABLE IF NOT EXISTS domains (

            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

            domain VARCHAR(255) NOT NULL UNIQUE,

            created_at DATETIME NOT NULL
                DEFAULT CURRENT_TIMESTAMP
        )
    ";

    $pdo->exec($sql);
}


function migrateAll(array $config): void
{
    migrateMessages($config);
    migrateIPretries($config);
    migrateDomains($config);
}


migrateAll($config);