<?php

function check_submit_limit(
    string $ip,
    string $domain,
    array $config
): bool {

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

                /* IP limits */
                SUM(
                    ip = :ip
                    AND created_at >= NOW() - INTERVAL 1 MINUTE
                ) AS ip_last_minute,

                SUM(
                    ip = :ip
                    AND created_at >= NOW() - INTERVAL 10 MINUTE
                ) AS ip_last_10_minutes,

                SUM(
                    ip = :ip
                    AND created_at >= NOW() - INTERVAL 1 HOUR
                ) AS ip_last_hour,


                /* Domain limits */
                SUM(
                    domain = :domain
                    AND created_at >= NOW() - INTERVAL 10 MINUTE
                ) AS domain_last_10_minutes,

                SUM(
                    domain = :domain
                    AND created_at >= NOW() - INTERVAL 1 HOUR
                ) AS domain_last_hour,


                /* Global limit */
                COUNT(*) AS global_last_hour

            FROM messages

            WHERE created_at >= NOW() - INTERVAL 1 HOUR
        ";


        $statement = $pdo->prepare($sql);

        $statement->execute([
            ':ip' => $ip,
            ':domain' => $domain
        ]);


        $result = $statement->fetch(PDO::FETCH_ASSOC);


        // IP limits

        if ($result['ip_last_minute'] >= $config['limits']['ip_last_minute']) {
            return false;
        }

        if ($result['ip_last_10_minutes'] >= $config['limits']['ip_last_10_minutes']) {
            return false;
        }

        if ($result['ip_last_hour'] >= $config['limits']['ip_last_hour']) {
            return false;
        }


        // Domain limits

        if ($result['domain_last_10_minutes'] >= $config['limits']['domain_last_10_minutes']) {
            return false;
        }

        if ($result['domain_last_hour'] >= $config['limits']['domain_last_hour']) {
            return false;
        }


        // Global limit

        if ($result['global_last_hour'] >= $config['limits']['global_last_hour']) {
            return false;
        }


        return true;

    } catch (PDOException $e) {

        // Fail closed if the rate limiter cannot check the database.
        return false;
    }
}

function check_email_limit(array $config): bool
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
            SELECT COUNT(*)
            FROM messages
            WHERE email_sent = 1
              AND email_sent_at >= CURRENT_DATE
              AND email_sent_at < CURRENT_DATE + INTERVAL 1 DAY
        ";

        $statement = $pdo->query($sql);

        $queryNumber = (int) $statement->fetchColumn();
        return $queryNumber < $config['limits']['email_limit_daily'];

    } catch (PDOException $e) {

        // Fail closed.
        return false;
    }
}