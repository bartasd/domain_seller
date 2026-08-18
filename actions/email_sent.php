<?php

function email_sent(int $id, array $config): bool
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
            UPDATE messages
            SET
                email_sent = 1,
                email_sent_at = NOW()
            WHERE id = :id
        ";


        $statement = $pdo->prepare($sql);

        $statement->execute([
            ':id' => $id
        ]);


        return $statement->rowCount() > 0;


    } catch (PDOException $e) {

        return false;
    }
}