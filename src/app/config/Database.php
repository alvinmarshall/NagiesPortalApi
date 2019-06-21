<?php


namespace App\config;

use PDO;
use PDOException;

class Database
{
    private $host = 'localhost';
    private $db_name = 'portal';
    private $usr = 'root';
    private $pwd = '';
    private static $conn;

    function getConnection()
    {
        self::$conn = null;
        try {
            self::$conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";", $this->usr, $this->pwd);
            self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            echo $exception;
        }
        return self::$conn;
    }
}