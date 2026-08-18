<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/get_message.php';

function send_email(
    int $id,
    array $config
): bool {

    $mail = new PHPMailer(true);

    $info = get_message($id, $config);

    try {

        // SMTP
        $mail->isSMTP();
        $mail->Host       = $config['mail']['smtp_host'];
        $mail->SMTPAuth   = true;
        $mail->Username   = $config['mail']['smtp_username'];
        $mail->Password   = $config['mail']['smtp_password'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = $config['mail']['smtp_port'];

        // Sender
        $mail->setFrom(
            $config['mail']['sender']['email'], 
            $config['mail']['sender']['name']
        );


        // Recipient
        $mail->addAddress(
            $config['mail']['recipient']['email'], 
            $config['mail']['recipient']['name']  
        );


        // Reply directly to proposer
        $mail->addReplyTo(
            $info['email'],
            $info['name']
        );


        /*
         * Escape user input before putting it into HTML.
         */
        $safeName = htmlspecialchars(
            $info['name'],
            ENT_QUOTES | ENT_SUBSTITUTE,
            'UTF-8'
        );

        $safeEmail = htmlspecialchars(
            $info['email'],
            ENT_QUOTES | ENT_SUBSTITUTE,
            'UTF-8'
        );

        $safeMessage = nl2br(
            htmlspecialchars(
                $info['message'],
                ENT_QUOTES | ENT_SUBSTITUTE,
                'UTF-8'
            )
        );


        /*
         * HTML email
         */
        $mail->isHTML(true);

        $mail->Subject = 'New domain proposition';

        $mail->Body = <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>New Proposition</title>
</head>

<body style="
    margin: 0;
    padding: 30px;
    background-color: #f4f4f4;
    font-family: Arial, Helvetica, sans-serif;
    color: #222222;
">

    <div style="
        max-width: 600px;
        margin: 0 auto;
        background: #ffffff;
        border-radius: 10px;
        overflow: hidden;
        border: 1px solid #dddddd;
    ">

        <div style="
            padding: 24px;
            background-color: #222222;
            color: #ffffff;
        ">
            <h1 style="
                margin: 0;
                font-size: 22px;
            ">
                New Domain Proposition
            </h1>

            <p style="
                margin: 8px 0 0;
                color: #cccccc;
                font-size: 14px;
            ">
                Someone has submitted a new offer.
            </p>
        </div>


        <div style="padding: 24px;">

            <div style="
                margin-bottom: 20px;
                padding: 16px;
                background-color: #f7f7f7;
                border-radius: 8px;
            ">

                <div style="
                    font-size: 12px;
                    color: #777777;
                    margin-bottom: 5px;
                ">
                    NAME
                </div>

                <div style="
                    font-size: 17px;
                    font-weight: bold;
                ">
                    {$safeName}
                </div>

            </div>


            <div style="
                margin-bottom: 20px;
                padding: 16px;
                background-color: #f7f7f7;
                border-radius: 8px;
            ">

                <div style="
                    font-size: 12px;
                    color: #777777;
                    margin-bottom: 5px;
                ">
                    EMAIL
                </div>

                <div style="
                    font-size: 16px;
                ">
                    <a
                        href="mailto:{$safeEmail}"
                        style="color: #2563eb;"
                    >
                        {$safeEmail}
                    </a>
                </div>

            </div>


            <div style="
                margin-bottom: 8px;
                font-size: 12px;
                color: #777777;
            ">
                PROPOSITION
            </div>

            <div style="
                padding: 18px;
                background-color: #fafafa;
                border-left: 4px solid #2563eb;
                border-radius: 4px;
                font-size: 16px;
                line-height: 1.6;
            ">
                {$safeMessage}
            </div>

        </div>


        <div style="
            padding: 16px 24px;
            background-color: #f7f7f7;
            color: #888888;
            font-size: 12px;
        ">
            Reply directly to this email to contact the proposer.
        </div>

    </div>

</body>
</html>
HTML;


        /*
         * Plain-text fallback.
         *
         * Some email clients don't display HTML.
         */
        $mail->AltBody =
            "New Domain Proposition\n\n" .
            "Name: " . $info['name'] . "\n" .
            "Email: " . $info['email'] . "\n\n" .
            "Proposition:\n" .
            $info['message'];


        $mail->send();

        return true;

    } catch (Exception $e) {

        return false;
    }
}