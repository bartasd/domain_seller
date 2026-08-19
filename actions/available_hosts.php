<?php

function checkWhitelist(string $domain, array $config): bool
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
            SELECT 1
            FROM domains
            WHERE domain = :domain
            LIMIT 1
        ";

        $statement = $pdo->prepare($sql);

        $statement->execute([
            ':domain' => $domain
        ]);

        return $statement->fetchColumn() !== false;

    } catch (PDOException $e) {

        // Database unavailable → don't allow access
        return false;
    }
}