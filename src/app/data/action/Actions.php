<?php


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

    function setInstructorAction()
    {
        if ($this->isUserAuthenticated()) {
            switch ($this->request) {
                case 'api/instructors':
                    if ($this->requestMethod == 'GET') {
                        $this->controller->index();
                        return;
                    }
                    $this->showBadRequestMessage();
                    break;
                case "api/instructors/$this->id":
                    if ($this->requestMethod == 'GET') {
                        $this->controller->show($this->id);
                        return;
                    }
                    $this->showBadRequestMessage();
                    break;
                case  "api/instructors/add":
                    if ($this->requestMethod == 'POST') {
                        $this->controller->create();
                        return;
                    }
                    $this->showBadRequestMessage();
                    break;
                default:
                    null;
            }
        }else{
            $this->showNotAuthenticatedMessage();
        }
    }

    function setStudentAction()
    {
        if (!$this->isUserAuthenticated()) {
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
            case "api/users":
                if ($this->requestMethod == 'GET') {
                    $this->controller->index();
                    return;
                }
                $this->showBadRequestMessage();
                break;

            case "api/users/login":
                if ($this->requestMethod == 'POST') {
                    $credentials = array(
                        "email" =>  self::$data->email ?? null,
                        "password" =>  self::$data->password ?? null
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
