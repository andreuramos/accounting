<?php

$dbHost = 'mysql';
$dbName = 'accounting-db';
$dbUsername = 'accounting';
$dbPassword = 'accpwd';

return [
    PDO::class => new PDO(
        "mysql:host=$dbHost;dbname=$dbName",
        $dbUsername,
        $dbPassword
    ),
];
