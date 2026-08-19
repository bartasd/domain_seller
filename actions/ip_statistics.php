<?php

function addIpRetry(string $ip, array $config){
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
            INSERT INTO ip_retries (ip, retries)
            VALUES (:ip, 1)
            ON DUPLICATE KEY UPDATE
                retries = retries + 1
        ";

        $statement = $pdo->prepare($sql);

        $statement->execute([
            ':ip' => $ip
        ]);


        $sql = "
            SELECT retries
            FROM ip_retries
            WHERE ip = :ip
        ";

        $statement = $pdo->prepare($sql);

        $statement->execute([
            ':ip' => $ip
        ]);

        $count = (int) $statement->fetchColumn();

        return $count;

    } catch (PDOException $e) {
        return null;
    }
}

function checkBlacklist(string $ip, array $config){
    return addIpRetry($ip, $config) > 50;
}