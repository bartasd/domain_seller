<?php

$filename = __DIR__ . '/../.env';

$data = <<<'ENV'
# DATABASE DATA

DB_HOST=localhost
DB_PORT=3306
DB_NAME=database_name
DB_USER=database_user
DB_PASS=database_password


# EMAIL DATA

MAIL_SMTP_HOST=smtp.gmail.com
MAIL_SMTP_PORT=587
MAIL_SMTP_USERNAME=yourtmptgmail@gmail.com
MAIL_SMTP_PASSWORD=xxxxxxxxxxxxxxxx

MAIL_SENDER_EMAIL=yourgmail@gmail.com
MAIL_SENDER_NAME="YOUR DOMAIN NAME OR SENDER NAME OR PROJECT NAME"

MAIL_RECIPIENT_EMAIL=yourgmail@gmail.com
MAIL_RECIPIENT_NAME="Your Name"


# SUBMIT LIMITS

# IP LIMITS
IP_LIMIT_MINUTE=1
IP_LIMIT_10_MINUTES=5
IP_LIMIT_HOUR=20

# DOMAIN LIMITS
DOMAIN_LIMIT_10_MINUTES=10
DOMAIN_LIMIT_HOUR=50

# GLOBAL LIMITS
GLOBAL_LIMIT_HOUR=100

# GLOBAL EMAIL SENDING LIMIT
EMAIL_LIMIT_DAILY=80


# LOCAL PROJECT NAME
# Example: http://localhost/local_project_name
LOCAL_PROJECT_NAME=local_project_name
ENV;

file_put_contents($filename, $data);