<?php


namespace App\data\model;


use App\data\IDataAccess;
use PDO;

class Students extends BaseModel implements IDataAccess
{
    public static $Students_No;
    public static $Students_Name;
    public static $Dob;
    public static $Gender;
    public static $dob;
    public static $Section_Name;
    public static $Faulty_Name;
    public static $Semester_Name;
    public static $Guardian_Name;
    public static $Guardian_No;
    public static $Image;

    private $dbConn;

    /**
     * Students constructor.
     * @param PDO $connection
     */
    public function __construct(PDO $connection)
    {
        $this->dbTable = 'student';
        $this->output['type'] = 'Students';
        $this->dbConn = $connection;
    }

    function add()
    {
        // TODO: Implement add() method.
    }

    function get()
    {
        return $this->getAllStudentsQuery();
    }

    function getById($id)
    {
        // TODO: Implement getById() method.
    }

    function update($id)
    {
        // TODO: Implement update() method.
    }

    function delete($id)
    {
        // TODO: Implement delete() method.
    }

    private function getAllStudentsQuery()
    {
        /** @noinspection SqlDialectInspection */
        $query = "SELECT
                            id, Students_No, 
                            Students_Name, Dob, Gender,
                            Admin_Date, Age, Section_Name,
                            Faculty_Name, Level_Name, Semester_Name,
                            Index_No, Guardian_Name, Guardian_No,
                            Image FROM " . $this->dbTable . " LIMIT 5";

        $stmt = $this->dbConn->prepare($query);
        return $stmt;
    }
}