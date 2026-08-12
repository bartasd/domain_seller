<?php

$config = require_once __DIR__ . '/../config/config.php';

function store(string $name, string $email, string $message): bool
{
    $host = $config['db']['host'];
    $database = $config['db']['name'];
    $username = $config['db']['user'];
    $password = $config['db']['pass'];

    try {
        $pdo = new PDO(
            "mysql:host=$host;dbname=$database;charset=utf8mb4",
            $username,
            $password
        );

        $pdo->setAttribute(
            PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION
        );

        $sql = "
            INSERT INTO messages (name, email, message)
            VALUES (:name, :email, :message)
        ";

        $statement = $pdo->prepare($sql);

        $statement->execute([
            ':name' => $name,
            ':email' => $email,
            ':message' => $message
        ]);

        return true;

    } catch (PDOException $e) {
        return false;
    }
}