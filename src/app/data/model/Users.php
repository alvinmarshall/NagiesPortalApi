<?php


namespace App\data\model;


use App\common\AppConstant;
use App\common\utils\Validator;
use Exception;
use PDO;

class Users extends BaseModel
{
    public static $full_name;
    public static $user_name;
    public static $image;
    public static $password;
    public static $level;
    private $dbConn;

    /**
     * Users constructor.
     * @param $dbConn
     */
    public function __construct(PDO $dbConn)
    {
        $this->dbConn = $dbConn;
    }


    /**
     * @param $username
     * @param $userType
     * @return bool
     */
    function verifyTeacherUsername($username, $userType): bool
    {
        /** @noinspection SqlDialectInspection */
        $query = "SELECT
                    id, Level_Name, Username, Password, Image  
                    FROM " . $userType . " WHERE Username = :username LIMIT 1";

        self::$user_name = Validator::singleInput($username);
        $stmt = $this->dbConn->prepare($query);
        $stmt->execute([':username' => self::$user_name]);
        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    /**@var string $Username
                     * @var string $id
                     * @var string $Password
                     * @var string $Level_Name
                     * @var string $Image
                     */
                    extract($row);
                    $this->id = $id;
                    self::$user_name = $Username;
                    self::$password = $Password;
                    self::$level = $Level_Name;
                    self::$image = $Image;
                }
                return true;
            }
        }
        return false;
    }

    /**
     * @param $username
     * @param $password
     * @param $userType
     * @return bool
     */
    function verifyParentUsername($username, $password, $userType): bool
    {
        /** @noinspection SqlDialectInspection */
        $query = "SELECT
                    Students_No, Level_Name, Index_No, Password, Image  
                    FROM " . $userType . " WHERE Index_No = ? AND Password = ? LIMIT 1";

        self::$user_name = Validator::singleInput($username);
        $stmt = $this->dbConn->prepare($query);
        $stmt->bindParam(1, $username);
        $stmt->bindParam(2, $password);
        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    /**@var string $Index_No
                     * @var string $Students_No
                     * @var string $Password
                     * @var string $Level_Name
                     * @var string $Image
                     */
                    extract($row);
                    $this->id = $Students_No;
                    self::$user_name = $Index_No;
                    self::$password = $Password;
                    self::$level = $Level_Name;
                    self::$image = $Image;
                }
                return true;
            }
        }
        return false;
    }


    /**
     * @param string $table
     * @param array $data
     * @return bool
     */
    function changeUserPassword(string $table, array $data):bool
    {
        $new = $data['new'];
        $confirm = $data['confirm'];
        $query = null;
        if ($new != $confirm) {
            $this->error['passwordError'] = "password mismatch";
            return false;
        }

        if (!$this->checkOldPassword($table, $data)) {
            $this->error['userError'] = "User not found";
            return false;
        }
        switch ($table) {
            case AppConstant::TABLE_STUDENT:
                $query = "UPDATE $table SET Password = ? WHERE Students_No = ?";
                return $this->prepareToChangePassword($query, $data);
            case AppConstant::TABLE_TEACHER:
                $query = "UPDATE $table SET Password = ? WHERE Teachers_No = ?";
                return $this->prepareToChangePassword($query, $data);
            default:
                return false;
        }
    }

    /**
     * @param string $query
     * @param array $data
     * @return bool
     */
    private function prepareToChangePassword(string $query, array $data):bool
    {
        if ($query == null) return false;

        try {
            $stmt = $this->dbConn->prepare($query);
            $stmt->bindParam(1, $data['new']);
            $stmt->bindParam(2, $data['id']);
            return $stmt->execute();
        } catch (Exception $e) {
            $this->error['mysql'] = $e->getMessage();
            return false;
        }
    }

    private function checkOldPassword(string $table, array $data): bool
    {
        $query = null;

        if ($table == AppConstant::TABLE_STUDENT) {
            $query = "SELECT id FROM $table WHERE Students_No = ? AND Password = ?";
        } else {
            $query = "SELECT id FROM $table WHERE id = ? AND Password = ?";
        }

        if ($query == null) {
            $this->error['table error'] = "no table specified";
            return false;
        }

        try {
            $stmt = $this->dbConn->prepare($query);
            $stmt->bindParam(1, $data['id']);
            $stmt->bindParam(2, $data['old']);
            $stmt->execute();
            if (!$stmt->rowCount() > 0) {
                $this->output['message'] = "Change password action failed";
                return false;
            }
            $this->output['message'] = "Change password action successful";
            return true;
        } catch (Exception $e) {
            $this->error['mysql'] = $e->getMessage();
            return false;
        }
    }

}