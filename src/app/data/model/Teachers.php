<?php


namespace App\data\model;


use App\auth\Authentication;
use App\data\IDataAccess;
use PDO;
use PDOStatement;
use Throwable;

class Teachers extends BaseModel implements IDataAccess
{
    private $dbConn;

    /**
     * Teachers constructor.
     * @param $dbConn
     */
    public function __construct(PDO $dbConn)
    {
        $this->dbConn = $dbConn;
    }


    function add()
    {
        // TODO: Implement add() method.
    }

    function get()
    {
        // TODO: Implement get() method.
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

    function getComplaints()
    {
        $level = Authentication::getDecodedData()['level'] ?? null;
        if ($level == null) {
            return null;
        }
        $this->output['type'] = 'Complaints';
        /** @noinspection SqlDialectInspection */
        $query = "SELECT 
                        id, Students_No, Students_Name, 
                        Level_Name, Guardian_Name, Guardian_No, 
                        Teachers_Name, Message, Message_Date FROM complaints
                         where Level_Name = ? ORDER BY Message_Date DESC ";
        $stmt = $this->dbConn->prepare($query);
        $stmt->bindParam(1, $level);
        return $stmt;
    }

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

    /**
     * @param $format
     * @param $destination
     * @param $dbTable
     * @return bool
     */
    function saveUploadFilePath($format, $destination,$dbTable): bool
    {
        $this->output['type'] = strtoupper($format);
        $table = $dbTable;
        $level = Authentication::getDecodedData()['level'] ?? null;
        $date = date('Y-m-d');
        $name = Authentication::getDecodedData()['username'] ?? null;
        $email = Authentication::getDecodedData()['username'] ?? null;
        if ($dbTable == 'report' || $dbTable == 'report_png'){
            if (isset($_POST['students_no'])  && isset($_POST['students_name'])){
                $level = $_POST['students_no'];
                $name = $_POST['students_name'];
            }else{
                $level = null;
                $name = null;
                $this->error['field'] = 'provide student report information fields';
                return false;
            }

        }

        /** @noinspection SqlDialectInspection */
        $query = "INSERT INTO ${table}
                        SET 
                            Students_No = :index,
                            Students_Name = :name,
                            Teachers_Email = :email,
                            Report_File = :destination,
                            Report_Date = :date
                        ";
        $stmt = $this->dbConn->prepare($query);
        $field = ['index', 'name', 'email', 'destination', 'date'];
        $input = [$level, $name, $email, $destination, $date];
        $this->output['message'] = 'File upload successful';
        $this->output['format'] = $format;
        if ($this->validateInput($field,$input)){
            return $this->prepareToInsertData($stmt, $input, $field);
        }
        return false;
    }

    private function bindAllParams(PDOStatement $stmt, $params, $fields)
    {
        foreach ($params as $param => $val) {
            $stmt->bindParam(':' . $fields[$param], $params[$param]);
        }
        return $stmt;
    }

    private function prepareToInsertData(PDOStatement $stmt, array $inputs, array $fields): bool
    {
        $this->bindAllParams($stmt, $inputs, $fields);
        try {
            $stmt->execute();
            $this->output['path'] = $inputs[3];
            $this->id = $this->dbConn->lastInsertId();
            return true;
        } catch (Throwable $e) {
            $this->error['mysql'] = $e->getMessage();
            return false;
        }
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
                $this->output['message'] = "fields not set";
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

}