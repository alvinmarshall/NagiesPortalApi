<?php


namespace App\data\model;


use App\auth\Authentication;
use App\common\utils\Validator;
use App\data\IDataAccess;
use PDO;
use PDOStatement;
use Throwable;

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

    /**
     * @return PDOStatement
     */
    function get()
    {
        return $this->getAllStudentsQuery();
    }

    /**
     * @param $from_num
     * @param $to_num
     * @return PDOStatement
     */
    function getWithPaginate($from_num, $to_num)
    {
        return $this->getStudentsWithPaginateQuery($from_num, $to_num);
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

    /**
     * @param array $complaintData
     * @return bool
     */
    function sendComplaints(array $complaintData)
    {
        $this->output['type'] = 'Complaints';
        /** @noinspection SqlDialectInspection */
        $query = "INSERT INTO complaints
                        SET 
                            Students_No = :sender,
                            Students_Name = :name,
                            Message = :content,
                            Level_Name = :level,
                            Message_Date = :date,
                            Guardian_Name = :guardian,
                            Guardian_No = :contact
                        ";
        $this->error = [];
        $stmt = $this->dbConn->prepare($query);
        $field = ['sender', 'name', 'content', 'level', 'date', 'guardian', 'contact'];
        $input = [$complaintData['sender'], $complaintData['name'], $complaintData['content'],
            $complaintData['level'], $complaintData['date'], $complaintData['guardian'], $complaintData['contact']];

        $isInputValid = $this->validateInput($field, $input);
        if ($isInputValid) {
            return $this->prepareToInsertData($stmt, $input, $field);
        }
        return false;
    }

    /**
     * @param $table
     * @return bool|PDOStatement
     */
    function getStudentReport($table): PDOStatement
    {
        $this->output['type'] = 'Reports';
        /** @noinspection SqlDialectInspection */
        $query = "SELECT 
                        id, Students_No, Students_Name,
                        Teachers_Email, Report_File, Report_Date FROM ${table}";
        $stmt = $this->dbConn->prepare($query);
        return $stmt;
    }

    /**
     * @return PDOStatement
     */
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

    /**
     * @param $from
     * @param $to
     * @return PDOStatement
     */
    private function getStudentsWithPaginateQuery($from, $to): PDOStatement
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
        $stmt->bindParam(1, $from, PDO::PARAM_INT);
        $stmt->bindParam(2, $to, PDO::PARAM_INT);
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

    /**
     * @return mixed
     */
    function getTotalStudent()
    {
        /** @noinspection SqlDialectInspection */
        $query = "SELECT COUNT(*) AS total_rows FROM " . $this->dbTable;
        $stmt = $this->dbConn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total_rows'];
    }

    /**
     * @param $fields
     * @param $inputs
     * @return bool
     */
    private function validateInput($fields, $inputs): bool
    {
        foreach ($inputs as $input => $data) {
            if (empty($data)) {
                $this->error[$fields[$input]] = "can't be empty";
            }
        }
        if (array_count_values($this->error)) {
            http_response_code(400);
            $this->output['status'] = 400;
            return false;
        }
        return true;
    }

    /**
     * @param PDOStatement $stmt
     * @param $params
     * @param $fields
     * @return PDOStatement
     */
    private function bindAllParams(PDOStatement $stmt, $params, $fields)
    {
        foreach ($params as $param => $val) {
            $stmt->bindParam(':' . $fields[$param], $params[$param]);
        }
        return $stmt;
    }

    /**
     * @param PDOStatement $stmt
     * @param array $inputs
     * @param array $fields
     * @return bool
     */
    private function prepareToInsertData(PDOStatement $stmt, array $inputs, array $fields): bool
    {
        $this->bindAllParams($stmt, $inputs, $fields);
        try {
            $stmt->execute();
            $this->id = $this->dbConn->lastInsertId();
            $this->output['message'] = 'your complaint is sent';
            return true;
        } catch (Throwable $e) {
            $this->error['mysql'] = $e->getMessage();
            return false;
        }
    }

    /**
     * @param $table
     * @param $format
     * @return bool|PDOStatement
     */
    function getAssignmentType($table, $format)
    {
        $this->output['type'] = 'Assignment' . $format;
        /** @noinspection SqlDialectInspection */
        $query = "SELECT
                    id, Students_No, Students_Name,
                    Teachers_Email, Report_File, Report_Date FROM " . $table;
        $stmt = $this->dbConn->prepare($query);
        return $stmt;
    }

    /**
     * @return bool|PDOStatement|null
     */
    function getMessages()
    {
        $level = Authentication::getDecodedData()['level'] ?? null;
        if ($level == null) {
            return null;
        }
        $this->output['type'] = 'Messages';
        /** @noinspection SqlDialectInspection */
        $query = "SELECT 
                        id, Message_BY, M_Date, Message, Message_Level, M_Read
                        FROM message WHERE Message_Level = ? ORDER BY M_Date DESC ";
        $stmt = $this->dbConn->prepare($query);
        $stmt->bindParam(1, $level);
        return $stmt;
    }
}