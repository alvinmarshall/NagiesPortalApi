<?php


namespace App\data\model;


use App\auth\Authentication;
use App\common\AppConstant;
use App\config\Database;
use App\data\IDataAccess;
use PDO;
use PDOStatement;
use Throwable;

class Teachers extends BaseModel implements IDataAccess
{
    private $dbConn;

    /**
     * Teachers constructor.
     * @param Database $connection
     */
    public function __construct(Database $connection)
    {
        $this->dbConn = $connection->getConnection();
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
        /** @noinspection SqlResolve */
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

    function getAnnouncement()
    {
        $level = 'administrator';
        $this->output['type'] = 'Announcement';
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
    function saveUploadFilePath($format, $destination, $dbTable): bool
    {
        switch ($dbTable) {
            case AppConstant::TABLE_CIRCULAR:
                $data = [
                    'cid' => isset($_POST['cid']) ? $_POST['cid'] : null,
                    'facultyName' => isset($_POST['facultyName']) ? $_POST['facultyName'] : null,
                    'fileName' => $destination
                ];
                return $this->saveCircularFileInfo($data, $dbTable);
            case AppConstant::TABLE_BILLING:
                $data = [
                    'studentNo' => isset($_POST['studentNo']) ? $_POST['studentNo'] : null,
                    'fileName' => $destination ?? null
                ];
                return $this->saveBillingFileInfo($data, $dbTable);
            default:
                return $this->saveStudentFileInfo($format, $destination, $dbTable);
        }

    }

    private function bindAllParams(PDOStatement $stmt, $params, $fields)
    {
        foreach ($params as $param => $val) {
            $stmt->bindParam(':' . $fields[$param], $params[$param]);
        }
        return $stmt;
    }

    private function prepareToInsertData(PDOStatement $stmt, array $inputs, array $fields, bool $isFile = true): bool
    {
        $this->bindAllParams($stmt, $inputs, $fields);
        try {
            $stmt->execute();
            if ($isFile) {
                $this->output['path'] = $inputs[3];
            }
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

    /**
     * @param array $messageData
     * @return bool
     */
    function sendMessage(array $messageData)
    {
        $data = $this->getTeacherInfo()[0];
        if ($data == null) return false;
        $date = date('Y-m-d');
        $this->output['type'] = 'Message';
        /** @noinspection SqlDialectInspection */
        $query = "INSERT INTO message
                        SET 
                            Message_BY = :sender,
                            Message = :message,
                            Message_Level = :level,
                            M_Date = :date
                        ";
        $this->error = [];
        $stmt = $this->dbConn->prepare($query);
        $field = ['sender', 'message', 'level', 'date'];
        $input = [$data['username'], $messageData['message'], $data['level'], $date];
        $this->output['level'] = $data['level'];
        $isInputValid = $this->validateInput($field, $input);
        if ($isInputValid) {
            return $this->prepareToInsertData($stmt, $input, $field, false);
        }
        return false;
    }

    private function getTeacherInfo()
    {
        $id = Authentication::getDecodedData()['id'] ?? '';
        /** @noinspection SqlDialectInspection */
        $query = "SELECT Username,Level_Name FROM teachers WHERE id = ? LIMIT 1";
        $stmt = $this->dbConn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        $num = $stmt->rowCount();
        $item = [];
        if ($num > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                /**
                 * @var string $Username
                 * @var string $Level_Name
                 */
                $item_data = [
                    "username" => $Username,
                    "level" => $Level_Name
                ];
                array_push($item, $item_data);
            }
            return $item;
        }
        return null;
    }

    function getTeacherDetails()
    {
        $this->output['type'] = 'TeacherProfile';
        $id = Authentication::getDecodedData()['id'];
        /** @noinspection SqlDialectInspection */
        $query = "SELECT id, Teachers_No, Teachers_Name, Dob, Gender, Contact, Admin_Date, Faculty_Name, Level_Name,
                Username, Image FROM teachers WHERE id = ?";
        $stmt = $this->dbConn->prepare($query);
        $stmt->bindParam(1, $id);
        return $stmt;

    }

    private function saveCircularFileInfo($data, $table)
    {
        /** @noinspection SqlResolve */
        $query = "INSERT INTO $table 
                    SET 
                        CID = :cid,
                        Faculty_Name = :facultyName,
                        FileName = :fileName,
                        CID_Date = :date
                    ";
        $this->output['type'] = 'Circular';
        $stmt = $this->dbConn->prepare($query);
        $date = date('Y-m-d');
        $field = ['cid', 'facultyName', 'date', 'fileName'];
        $input = [$data['cid'], $data['facultyName'], $date, $data['fileName']];
        if (!$this->validateInput($field, $input)) return false;
        return $this->prepareToInsertData($stmt, $input, $field);
    }

    /**
     * @param $format
     * @param $destination
     * @param $dbTable
     * @return bool
     */
    private function saveStudentFileInfo($format, $destination, $dbTable): bool
    {
        $this->output['type'] = strtoupper($format);
        $table = $dbTable;
        $level = Authentication::getDecodedData()['level'] ?? null;
        $date = date('Y-m-d');
        $name = Authentication::getDecodedData()['username'] ?? null;
        $email = Authentication::getDecodedData()['username'] ?? null;
        if ($dbTable == 'report' || $dbTable == 'report_png') {
            if (isset($_POST['students_no']) && isset($_POST['students_name'])) {
                $level = $_POST['students_no'];
                $name = $_POST['students_name'];
            } else {
                $level = null;
                $name = null;
                $this->error['field'] = 'provide student report information fields';
                return false;
            }

        }

        /** @noinspection SqlResolve */
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
        if ($this->validateInput($field, $input)) {
            return $this->prepareToInsertData($stmt, $input, $field);
        }
        return false;
    }

    function saveBillingFileInfo(array $data, $table)
    {
        $username = Authentication::getDecodedData()['username'] ?? null;
        $student_no = isset($data['studentNo']) ? $data['studentNo'] : null;
        $student_name = null;
        if (!empty($student_no)) {
            $student_name = $this->getStudentInformation($student_no)[0]['studentName'] ?? null;
        }

        /** @noinspection SqlResolve */
        $query = "INSERT INTO $table
                    SET
                        Students_No = :studentNo,
                        Students_Name = :studentName,
                        Uploader = :username,
                        Bill_File = :destination,
                        Report_Date = :date
                    ";
        $this->output['type'] = 'Billing';
        $stmt = $this->dbConn->prepare($query);
        $date = date('Y-m-d');
        $field = ['studentNo', 'studentName', 'username', 'destination', 'date'];
        $input = [$data['studentNo'], $student_name, $username, $data['fileName'], $date];
        if (!$this->validateInput($field, $input)) return false;
        return $this->prepareToInsertData($stmt, $input, $field);
    }

    private function getStudentInformation($studentNo)
    {
        $item = [];
        if (empty($studentNo)) return $item;
        $query = "SELECT Students_Name FROM student WHERE Students_No = ?";
        $stmt = $this->dbConn->prepare($query);
        $stmt->bindParam(1, $studentNo);
        $stmt->execute();
        $num = $stmt->rowCount();
        if ($num == 0) return $item;

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            /**
             * @var string $Students_Name
             */
            $student_item = [
                'studentName' => $Students_Name
            ];
            array_push($item, $student_item);
        }
        return $item;
    }

}