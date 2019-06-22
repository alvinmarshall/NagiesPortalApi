<?php


namespace App\data\model;


use App\common\utils\Validator;
use App\data\IDataAccess;
use PDO;
use PDOStatement;

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
    function getWithPaginate($from_num,$to_num){
        return $this->getStudentsWithPaginateQuery($from_num,$to_num);
    }

    function getById($id)
    {
        return $this->getStudentQuery($id);
    }

    function update($id)
    {
        // TODO: Implement update() method.
    }

    function delete($id)
    {
        // TODO: Implement delete() method.
    }

    private function getAllStudentsQuery(): PDOStatement
    {
        /** @noinspection SqlDialectInspection */
        $query = "SELECT
                            id, Students_No, 
                            Students_Name, Dob, Gender,
                            Admin_Date, Age, Section_Name,
                            Faculty_Name, Level_Name, Semester_Name,
                            Index_No, Guardian_Name, Guardian_No,
                            Image FROM " . $this->dbTable . " LIMIT 5,15";

        $stmt = $this->dbConn->prepare($query);
        return $stmt;
    }

    private function getStudentsWithPaginateQuery($from,$to): PDOStatement
    {
        /** @noinspection SqlDialectInspection */
        $query = "SELECT
                            id, Students_No, 
                            Students_Name, Dob, Gender,
                            Admin_Date, Age, Section_Name,
                            Faculty_Name, Level_Name, Semester_Name,
                            Index_No, Guardian_Name, Guardian_No,
                            Image FROM " . $this->dbTable . " LIMIT ?,? ";

        $stmt = $this->dbConn->prepare($query);
        $stmt->bindParam(1,$from,PDO::PARAM_INT);
        $stmt->bindParam(2,$to,PDO::PARAM_INT);
        return $stmt;
    }

    /**
     * @param $id
     * @return bool|PDOStatement
     */
    private function getStudentQuery($id)
    {
        /** @noinspection SqlDialectInspection */
        $query = "SELECT
                            id, Students_No, 
                            Students_Name, Dob, Gender,
                            Admin_Date, Age, Section_Name,
                            Faculty_Name, Level_Name, Semester_Name,
                            Index_No, Guardian_Name, Guardian_No,
                            Image FROM " . $this->dbTable . " WHERE id = :id";

        $stmt = $this->dbConn->prepare($query);
        $id = Validator::singleInput($id);
        $stmt->bindParam(':id', $id);
        return $stmt;
    }

    function getTotalStudent(){
        /** @noinspection SqlDialectInspection */
        $query = "SELECT COUNT(*) AS total_rows FROM ".$this->dbTable;
        $stmt = $this->dbConn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total_rows'];
    }
}