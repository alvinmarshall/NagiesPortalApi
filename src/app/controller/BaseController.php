<?php


namespace App\controller;


use App\config\Database;
use PDO;

abstract class BaseController
{
    /**
     * @var PDO
     */
    protected $conn;
    /**
     * BaseController constructor.
     */
    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    abstract function index();
    abstract function show($id);
    abstract function create();
    abstract function update($id);
    abstract function delete($id);
}