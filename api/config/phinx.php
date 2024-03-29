<?php

require_once __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/..');
$dotenv->load();

return
[
    'paths' => [
        'migrations' => '%%PHINX_CONFIG_DIR%%/../database/migrations',
        'seeds' => '%%PHINX_CONFIG_DIR%%/../database/seeds',
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_environment' => 'development',
        'production' => [
            'adapter' => 'mysql',
            'host' => 'localhost',
            'name' => 'production_db',
            'user' => 'root',
            'pass' => '',
            'port' => '3306',
            'charset' => 'utf8',
        ],
        'development' => [
            'adapter' => 'mysql',
            'host' => env('DB_HOST'),
            'name' => env('DB_NAME'),
            'user' => env('DB_USER'),
            'pass' => env('DB_PWD'),
            'port' => env('DB_PORT'),
            'charset' => 'utf8',
        ],
        'testing' => [
            'adapter' => 'sqlite',
            'host' => 'localhost',
            'name' => 'tmp/testing_db',
            'user' => '',
            'pass' => '',
            'port' => '3306',
            'charset' => 'utf8',
        ],
    ],
    'version_order' => 'creation'
];
