<?php

require __DIR__ . '/send_email.php';
require __DIR__ . '/email_sent.php';

function resend_messages(array $config): void
{
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
            SELECT *
            FROM messages
            WHERE email_sent = 0
              AND email_send_retries < 5
            ORDER BY created_at ASC
        ";

        $statement = $pdo->prepare($sql);
        $statement->execute();

        while ($message = $statement->fetch(PDO::FETCH_ASSOC)) {

            $id = $message['id'];

            if (send_email($id, $config)) {
                email_sent($id, $config);
            }

        }

    } catch (PDOException $e) {

        // Log error

    }
}

function check_worker_state(array $config){
    $file = __DIR__ . '/../config/worker_date.txt';
    $lastDate = trim(file_get_contents($file));
    $now = date('Y-m-d');
    if($lastDate != $now){
        file_put_contents($file, $now);
        resend_messages($config);
    }
}

