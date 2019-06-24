<?php


namespace App\data\model;


use App\auth\Authentication;
use App\data\IDataAccess;
use PDO;

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
}