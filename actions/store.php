<?php

function store(
    string $name,
    string $email,
    string $message,
    string $ip,
    string $domain,
    array $config
): ?int {

    $host = $config['db']['host'];
    $port = $config['db']['port'];
    $database = $config['db']['name'];
    $username = $config['db']['user'];
    $password = $config['db']['pass'];

    try {

        $pdo = new PDO(
            "mysql:host=$host;port=$port;dbname=$database;charset=utf8mb4",
            $username,
            $password
        );

        $pdo->setAttribute(
            PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION
        );


        $sql = "
            INSERT INTO messages (
                name,
                email,
                message,
                ip,
                domain
            )
            VALUES (
                :name,
                :email,
                :message,
                :ip,
                :domain
            )
        ";


        $statement = $pdo->prepare($sql);


        $statement->execute([
            ':name' => $name,
            ':email' => $email,
            ':message' => $message,
            ':ip' => $ip,
            ':domain' => $domain
        ]);


        return (int) $pdo->lastInsertId();


    } catch (PDOException $e) {

        return null;
    }
}