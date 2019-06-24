<?php /** @noinspection PhpUndefinedMethodInspection */


namespace App\data\action;


use App\auth\Authentication;

class Actions
{
    private $request;
    private $id;
    private $controller;
    private $requestMethod;
    public static $data;
    public static $token;
    public static $userType;
    public $pageNo;

    /**
     * Actions constructor.
     * @param $request
     * @param $id
     * @param $controller
     * @param $requestMethod
     */
    public function __construct($request, $id, $controller, $requestMethod)
    {
        $this->request = $request;
        $this->id = $id;
        $this->controller = $controller;
        $this->requestMethod = $requestMethod;
    }

    private function isUserAuthenticated()
    {
        if (self::$token == null) return false;
        if (Authentication::isJWTTokenValid(self::$token)) {
            return true;
        }
        return false;
    }

    function setTeachersAction()
    {
        if ($this->isUserAuthenticated()) {
            switch ($this->request) {
                case '/api/teachers':
                    if ($this->requestMethod == 'GET') {
                        $this->controller->index();
                        return;
                    }
                    $this->showBadRequestMessage();
                    break;
                case "/api/teachers/$this->id":
                    if ($this->requestMethod == 'GET') {
                        $this->controller->show($this->id);
                        return;
                    }
                    $this->showBadRequestMessage();
                    break;
                case  "/api/teachers/assignment_pdf":
                    if ($this->requestMethod == 'GET') {
                        $this->controller->assignmentFormat('pdf');
                        return;
                    }
                    $this->showBadRequestMessage();
                    break;
                case  "/api/teachers/assignment_image":
                    if ($this->requestMethod == 'GET') {
                        $this->controller->assignmentFormat('jpeg');
                        return;
                    }
                    $this->showBadRequestMessage();
                    break;
                case  "/api/teachers/complaints":
                    if ($this->requestMethod == 'GET') {
                        $this->controller->getComplaints();
                        return;
                    }
                    $this->showBadRequestMessage();
                    break;
                default:
                    null;
            }
        } else {
            $this->showNotAuthenticatedMessage();
        }
    }

    function setStudentAction()
    {
        if ($this->isUserAuthenticated()) {
            switch ($this->request) {
                case "/api/students":
                    if ($this->requestMethod == 'GET') {
                        $this->controller->index();
                        return;
                    }
                    $this->showBadRequestMessage();
                    break;
                case "/api/students/$this->id":
                    if ($this->requestMethod == 'GET') {
                        $this->controller->show($this->id);
                        return;
                    }
                    $this->showBadRequestMessage();
                    break;
                case "/api/students/add":
                    if ($this->requestMethod == 'POST') {
                        $this->controller->create();
                        return;
                    }
                    $this->showBadRequestMessage();
                    break;
                case "/api/students/":
                    if ($this->requestMethod == 'GET') {
                        $this->controller->paginateStudent();
                        return;
                    }
                    $this->showBadRequestMessage();
                    break;
                case "/api/students/page/$this->pageNo":
                    if ($this->requestMethod == 'GET') {
                        if (empty($this->pageNo)) {
                            $this->pageNo = 1;
                        }
                        $from_num = (5 * $this->pageNo) - 5;
                        $this->controller->paginateStudent($this->pageNo, $from_num, 5);
                        return;
                    }
                    $this->showBadRequestMessage();
                    break;
                case "/api/students/report_pdf":
                    if ($this->requestMethod == 'GET') {
                        $this->controller->getReport('pdf');
                        return;
                    }
                    $this->showBadRequestMessage();
                    break;
                case "/api/students/report_image":
                    if ($this->requestMethod == 'GET') {
                        $this->controller->getReport('jpeg');
                        return;
                    }
                    $this->showBadRequestMessage();
                    break;
                default:
                    null;
            }
        } else {
            $this->showNotAuthenticatedMessage();
        }
    }

    function setUserAction()
    {
        switch ($this->request) {
            case "/api/users":
                if ($this->requestMethod == 'GET') {
                    $this->controller->index();
                    return;
                }
                $this->showBadRequestMessage();
                break;

            case "/api/users/parent":
                if ($this->requestMethod == 'POST') {
                    $credentials = array(
                        "username" => self::$data->username ?? null,
                        "password" => self::$data->password ?? null,
                        "user" => self::$userType
                    );
                    $this->controller->authenticateUser($credentials);
                    return;
                }
                $this->showBadRequestMessage();
                break;

            case "/api/users/teacher":
                if ($this->requestMethod == 'POST') {
                    $credentials = array(
                        "username" => self::$data->username ?? null,
                        "password" => self::$data->password ?? null,
                        "user" => self::$userType
                    );
                    $this->controller->authenticateUser($credentials);
                    return;
                }
                $this->showBadRequestMessage();
                break;
        }
    }

    private function showBadRequestMessage()
    {
        http_response_code(405);
        echo json_encode(array("status" => 405));
    }

    private function showNotAuthenticatedMessage()
    {
        http_response_code(401);
        echo json_encode(array("status" => 401, "message" => "You're not authorised for this action"));
    }
}
