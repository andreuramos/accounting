<?php

use App\User\Infrastructure\Auth\JWTDecoder;
use App\User\Infrastructure\Auth\JWTGenerator;
use App\User\Infrastructure\Auth\JWTRefreshTokenGenerator;

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
