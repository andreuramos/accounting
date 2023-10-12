<?php

use App\Infrastructure\Auth\JWTDecoder;
use App\Infrastructure\Auth\JWTGenerator;
use App\Infrastructure\Auth\JWTRefreshTokenGenerator;

require_once __DIR__ . '/../../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../..');
$dotenv->load();

$driver = env('DB_DRIVER');
$dbHost = env('DB_HOST');
$dbName = env('DB_NAME');
$dbUsername = env('DB_USER');
$dbPassword = env('DB_PWD');

return [
    PDO::class => new PDO(
        "$driver:host=$dbHost;dbname=$dbName",
        $dbUsername,
        $dbPassword
    ),
    JWTGenerator::class => new JWTGenerator(
        env('JWT_SIGNATURE_KEY'),
        env('JWT_TTL'),
    ),
    JWTDecoder::class => new JWTDecoder(
        env('JWT_SIGNATURE_KEY')
    ),
    JWTRefreshTokenGenerator::class => new JWTRefreshTokenGenerator(
        env('JWT_SIGNATURE_KEY'),
        env('JWT_REFRESH_TTL')
    )
];
