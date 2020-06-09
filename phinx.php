<?php
$host = getenv('DB_HOST');
$dbName = getenv('DB_DATABASE');
$dbUser = getenv('DB_USER');
$dbPassword = getenv('DB_PASSWORD');

$pdo = new PDO("mysql:host=$host;dbname=$dbName", $dbUser, $dbPassword);

return [
    'environments' => [
        'default_database' => 'development',
        'development' => [
            'name' => $dbName,
            'connection' => $pdo
        ]
    ],

    "paths" => [
        "migrations" => "Migrations"
        ]
];