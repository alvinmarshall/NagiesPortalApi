<?php


namespace App\config;

use PDO;
use PDOException;

class Database
{
    private static $conn;

    function getConnection()
    {
        self::$conn = null;
        try {
            self::$conn = new PDO("mysql:host=" . getenv('DB_HOST') .
                ";dbname=" . getenv('DB_NAME') . ";", getenv('DB_USR'), getenv('DB_PWD'));
            self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            echo $exception;
        }
        return self::$conn;
    }
}