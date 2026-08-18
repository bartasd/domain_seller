<?php

function get_message(int $id, array $config): ?array
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
            SELECT
                name,
                email,
                message
            FROM messages
            WHERE id = :id
            LIMIT 1
        ";

        $statement = $pdo->prepare($sql);

        $statement->execute([
            ':id' => $id
        ]);

        $message = $statement->fetch(PDO::FETCH_ASSOC);

        if ($message === false) {
            return null;
        }

        return $message;

    } catch (PDOException $e) {
        return null;
    }
}