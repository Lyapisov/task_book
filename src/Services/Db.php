<?php

namespace TaskManager\Services;

use PDO;

class Db
{
    public static function getConnection()
    {
        $host = getenv('DB_HOST');
        $dbName = getenv('DB_DATABASE');
        $dbUser = getenv('DB_USER');
        $dbPassword = getenv('DB_PASSWORD');

        $pdo = new PDO("mysql:host=$host;dbname=$dbName", $dbUser, $dbPassword);
        $pdo->exec("set names utf8");
        return $pdo;
    }

}
