<?php

function redirect_home(array $config): void
{
    $local_project_name = $config['site']['local_url'];
    $host = $_SERVER['HTTP_HOST'] === 'localhost' ? "localhost/$local_project_name" : $_SERVER['HTTP_HOST'];

    header("Location: https://$host/");
    die;
}