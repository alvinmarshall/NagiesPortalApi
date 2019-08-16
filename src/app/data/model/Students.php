<?php /** @noinspection SqlIdentifier */

/** @noinspection SqlResolve */


namespace App\data\model;


use App\auth\Authentication;
use App\common\utils\Validator;
use App\config\Database;
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
     * @param Database $connection
     */
    public function __construct(Database $connection)
    {
        $this->dbTable = 'student';
        $this->output['type'] = 'Students';
        $this->dbConn = $connection->getConnection();
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

    function getStudentDetails()
    {
        $this->output['type'] = 'StudentProfile';
        $student_no = Authentication::getDecodedData()['id'];
        $query = "SELECT * FROM $this->dbTable WHERE Students_No = ?";
        $stmt = $this->dbConn->prepare($query);
        $stmt->bindParam(1, $student_no);
        return $stmt;
    }

    /**
     * @param array $complaintData
     * @return bool
     */
    function sendComplaints(array $complaintData)
    {
        $data = $this->getStudentInfo()[0];
        if ($data == null) return false;
        $date = date('Y-m-d');
        $this->output['type'] = 'Complaints';
        /** @noinspection SqlDialectInspection */
        $query = "INSERT INTO complaints
                        SET 
                            Students_No = :sender,
                            Students_Name = :name,
                            Message = :message,
                            Level_Name = :level,
                            Message_Date = :date,
                            Guardian_Name = :guardian,
                            Guardian_No = :contact,
                            Teachers_Name = :teacher
                        ";
        $this->error = [];
        $stmt = $this->dbConn->prepare($query);
        $field = ['sender', 'name', 'message', 'level', 'date', 'guardian', 'contact', 'teacher'];
        $input = [$data['sender'], $data['name'], $complaintData['message'],
            $data['level'], $date, $data['guardian'], $data['contact'], $data['teacher']];

        $isInputValid = $this->validateInput($field, $input);
        if ($isInputValid) {
            return $this->prepareToInsertData($stmt, $input, $field);
        }
        return false;
    }

    private function getStudentInfo()
    {
        $id = Authentication::getDecodedData()['id'] ?? '';
        $level = Authentication::getDecodedData()['level'] ?? '';
        /** @noinspection SqlDialectInspection */
        $query = "SELECT 
                        s.Guardian_Name,s.Guardian_No,t.Teachers_Name,s.Students_Name ,s.Faculty_Name
                    FROM  student s,teachers t  WHERE Students_No = ? AND  t.Level_Name = ?";
        $stmt = $this->dbConn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->bindParam(2, $level);
        $stmt->execute();
        $num = $stmt->rowCount();
        $item = [];
        if ($num > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                /**
                 * @var string $Teachers_Name
                 * @var string $Guardian_No
                 * @var string $Guardian_Name
                 * @var string $Students_Name
                 * @var string $Faculty_Name
                 */
                $item_data = [
                    "teacher" => $Teachers_Name,
                    "guardian" => $Guardian_Name,
                    "contact" => $Guardian_No,
                    "level" => $level,
                    "sender" => $id,
                    "name" => $Students_Name,
                    'faculty' => $Faculty_Name
                ];
                array_push($item, $item_data);
            }
            return $item;
        }
        return null;
    }

    /**
     * @param $table
     * @return bool|PDOStatement
     */
    function getStudentReport($table): PDOStatement
    {
        $student_no = Authentication::getDecodedData()['id'];
        $this->output['type'] = 'Reports';
        /** @noinspection SqlDialectInspection */
        $query = "SELECT 
                        Students_Name,
                        Teachers_Email, Report_File, Report_Date FROM ${table} WHERE Students_No = ?";
        $stmt = $this->dbConn->prepare($query);
        $stmt->bindParam(1, $student_no);
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
                            Image FROM " . $this->dbTable . " LIMIT 15";

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
        $level = Authentication::getDecodedData()['level'];
        $this->output['type'] = 'Assignment' . $format;
        $query = "SELECT
                    Students_Name,
                    Teachers_Email, Report_File, Report_Date FROM  $table WHERE Students_No = ?";
        $stmt = $this->dbConn->prepare($query);
        $stmt->bindParam(1, $level);
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
        $query = "SELECT 
                        id, Message_BY, M_Date, Message, Message_Level, M_Read
                        FROM message WHERE Message_Level = ? ORDER BY M_Date DESC ";
        $stmt = $this->dbConn->prepare($query);
        $stmt->bindParam(1, $level);
        return $stmt;
    }

    /**
     * @return bool|PDOStatement|null
     */
    function getClassTeachers()
    {
        $this->output['type'] = 'Teachers';
        $level = Authentication::getDecodedData()['level'] ?? null;
        if ($level == null) return null;
        $query = "SELECT Teachers_No, Teachers_Name, Gender, Contact, Image FROM teachers WHERE Level_Name = ?";
        $stmt = $this->dbConn->prepare($query);
        $stmt->bindParam(1, $level);
        return $stmt;
    }

    function getStudentCircular()
    {
        $this->output['type'] = 'Circular';
        $faculty = $this->getStudentInfo()[0]['faculty'];
        $query = "SELECT c.id, c.CID, c.FileName, c.CID_Date  FROM circular c 
                WHERE c.Faculty_Name = ? ORDER BY c.CID_Date DESC ";
        $stmt = $this->dbConn->prepare($query);
        $stmt->bindParam(1, $faculty);
        return $stmt;
    }

    function getStudentBilling()
    {
        $this->output['type'] = 'Billing';
        $id = Authentication::getDecodedData()['id'] ?? null;
        $query = "SELECT id, Students_No, Students_Name, Uploader, Bill_File, Report_Date 
                FROM billing WHERE Students_No = ? ORDER BY Report_Date DESC ";
        $stmt = $this->dbConn->prepare($query);
        $stmt->bindParam(1, $id);
        return $stmt;
    }
}