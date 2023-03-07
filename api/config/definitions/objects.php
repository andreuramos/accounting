<?php

require_once __DIR__ . '/../../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../..');
$dotenv->load();

$dbHost = env('DB_HOST');
$dbName = env('DB_NAME');
$dbUsername = env('DB_USER');
$dbPassword = env('DB_PWD');

return [
    PDO::class => new PDO(
        "mysql:host=$dbHost;dbname=$dbName",
        $dbUsername,
        $dbPassword
    ),
];
