<?php


namespace App\test\db;


use PDO;
use PHPUnit\DbUnit\Database\Connection;
use PHPUnit\DbUnit\TestCaseTrait;
use PHPUnit\Framework\TestCase;

abstract class dbTest extends TestCase
{
    use TestCaseTrait;
    static $pdo;
    private $conn;


    /**
     * Returns the test database connection.
     *
     * @return Connection
     */
    protected function getConnection()
    {
        if ($this->conn === null){
            if (self::$pdo == null){
                self::$pdo = new PDO('mysql:dbname=portal;host=localhost','root','');
            }
            $this->conn = $this->createDefaultDBConnection(self::$pdo,'portal');
        }
        return $this->conn;
    }

}