<?php


namespace App\data\model;


use App\common\utils\Validator;
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
    function verifyTeacherUsername($username, $userType):bool
    {
        /** @noinspection SqlDialectInspection */
        $query = "SELECT
                    id, Level_Name, Username, Password, Image  
                    FROM " . $userType . " WHERE Username = :username LIMIT 1";

        self::$user_name = Validator::singleInput($username,'username');
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
     * @param $userType
     * @return bool
     */
    function verifyParentUsername($username, $userType):bool
    {
        /** @noinspection SqlDialectInspection */
        $query = "SELECT
                    id, Level_Name, Index_No, Password, Image  
                    FROM " . $userType . " WHERE Index_No = :username LIMIT 1";

        self::$user_name = Validator::singleInput($username);
        $stmt = $this->dbConn->prepare($query);
        $stmt->execute([':username' => self::$user_name]);
        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    /**@var string $Index_No
                     * @var string $id
                     * @var string $Password
                     * @var string $Level_Name
                     * @var string $Image
                     */
                    extract($row);
                    $this->id = $id;
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

}