<?php /** @noinspection PhpUndefinedMethodInspection */


namespace App\data\action;


use App\auth\Authentication;
use App\common\AppConstant;


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
            $role = Authentication::getDecodedData()['role'];
            if ($role != AppConstant::USER_TEACHER) {
                $this->showNotAuthenticatedMessage();
                return;
            }
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
                        $this->controller->assignmentFormat(AppConstant::FORMAT_PDF);
                        return;
                    }
                    $this->showBadRequestMessage();
                    break;
                case  "/api/teachers/assignment_image":
                    if ($this->requestMethod == 'GET') {
                        $this->controller->assignmentFormat(AppConstant::FORMAT_IMAGE);
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
                case  "/api/teachers/announcement":
                    if ($this->requestMethod == 'GET') {
                        $this->controller->getAnnouncement();
                        return;
                    }
                    $this->showBadRequestMessage();
                    break;
                case  "/api/teachers/upload_assignment_pdf":
                    if ($this->requestMethod == 'POST') {
                        $this->controller->uploadFile(AppConstant::TABLE_ASSIGNMENT_PDF);
                        return;
                    }
                    $this->showBadRequestMessage();
                    break;

                case  "/api/teachers/upload_assignment_image":
                    if ($this->requestMethod == 'POST') {
                        $this->controller->uploadFile(AppConstant::TABLE_ASSIGNMENT_IMAGE);
                        return;
                    }
                    $this->showBadRequestMessage();
                    break;

                case  "/api/teachers/upload_report_pdf":
                    if ($this->requestMethod == 'POST') {
                        $this->controller->uploadFile(AppConstant::TABLE_REPORT_PDF);
                        return;
                    }
                    $this->showBadRequestMessage();
                    break;

                case  "/api/teachers/upload_report_image":
                    if ($this->requestMethod == 'POST') {
                        $this->controller->uploadFile(AppConstant::TABLE_REPORT_IMAGE);
                        return;
                    }
                    $this->showBadRequestMessage();
                    break;

                case "/api/teachers/change_password":
                    $this->changePassword($this->requestMethod);
                    break;

                case "/api/teachers/send_message":
                    if ($this->requestMethod == 'POST') {
                        $this->controller->sendMessage(self::$data);
                        return;
                    }
                    $this->showBadRequestMessage();
                    break;

                case "/api/teachers/profile":
                    if ($this->requestMethod == 'GET') {
                        $this->controller->getTeacherProfile();
                        return;
                    }
                    $this->showBadRequestMessage();
                    break;

                case "/api/teachers/upload_circular":
                    if ($this->requestMethod == 'POST') {
                        $this->controller->uploadFile(AppConstant::TABLE_CIRCULAR, AppConstant::DIR_CIRCULAR);
                        return;
                    }
                    $this->showBadRequestMessage();
                    break;

                case "/api/teachers/upload_billing":
                    if ($this->requestMethod == 'POST') {
                        $this->controller->uploadFile(AppConstant::TABLE_BILLING, AppConstant::DIR_BILLING);
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
            $role = Authentication::getDecodedData()['role'];
            if ($role != AppConstant::USER_PARENT) {
                $this->showNotAuthenticatedMessage();
                return;
            }
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
                        $this->controller->getReport(AppConstant::FORMAT_PDF);
                        return;
                    }
                    $this->showBadRequestMessage();
                    break;
                case "/api/students/report_image":
                    if ($this->requestMethod == 'GET') {
                        $this->controller->getReport(AppConstant::FORMAT_IMAGE);
                        return;
                    }
                    $this->showBadRequestMessage();
                    break;
                case "/api/students/complaints":
                    if ($this->requestMethod == 'POST') {
                        $this->controller->sendComplaints(self::$data);
                        return;
                    }
                    $this->showBadRequestMessage();
                    break;
                case  "/api/students/assignment_pdf":
                    if ($this->requestMethod == 'GET') {
                        $this->controller->assignmentFormat(AppConstant::FORMAT_PDF);
                        return;
                    }
                    $this->showBadRequestMessage();
                    break;
                case  "/api/students/assignment_image":
                    if ($this->requestMethod == 'GET') {
                        $this->controller->assignmentFormat(AppConstant::FORMAT_IMAGE);
                        return;
                    }
                    $this->showBadRequestMessage();
                    break;
                case  "/api/students/messages":
                    if ($this->requestMethod == 'GET') {
                        $this->controller->getMessages();
                        return;
                    }
                    $this->showBadRequestMessage();
                    break;
                case "/api/students/profile":
                    if ($this->requestMethod == 'GET') {
                        $this->controller->getProfile();
                        return;
                    }
                    $this->showBadRequestMessage();
                    break;
                case "/api/students/download":
                    if ($this->requestMethod == 'POST') {
                        $this->controller->getFile(self::$data);
                        return;
                    }
                    $this->showBadRequestMessage();
                    break;

                case "/api/students/change_password":
                    $this->changePassword($this->requestMethod);
                    break;

                case "/api/students/teachers":
                    if ($this->requestMethod == 'GET') {
                        $this->controller->getTeachers();
                        return;
                    }
                    $this->showBadRequestMessage();
                    break;

                case "/api/students/circular":
                    if ($this->requestMethod == 'GET') {
                        $this->controller->getCircular();
                        return;
                    }
                    $this->showBadRequestMessage();
                    break;

                case "/api/students/billing":
                    if ($this->requestMethod == 'GET') {
                        $this->controller->getBilling();
                        return;
                    }
                    $this->showBadRequestMessage();
                    break;

                case "/api/students/announcement":
                    if ($this->requestMethod == 'GET') {
                        $this->controller->getAnnouncement();
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

            case "/api/users/teacher":
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

            default:
                null;
        }
    }

    function setFirebaseMessagingAction()
    {
        switch ($this->request) {
            case '/api/message/single':
                if ($this->requestMethod == 'POST') {
                    $notificationBody = [
                        'deviceId' => self::$data->deviceId ?? null,
                        'content' => self::$data->message ?? null,
                        'title' => self::$data->title ?? null,
                        'type' => self::$data->type ?? null
                    ];
                    $this->controller->single($notificationBody);
                    return;
                }
                $this->showBadRequestMessage();
                break;
            case '/api/message/group':
                if ($this->requestMethod == 'POST') {
                    $notificationBody = [
                        'groupId' => self::$data->groupId ?? null,
                        'content' => self::$data->message ?? null,
                        'title' => self::$data->title ?? null,
                        'type' => self::$data->type ?? null
                    ];
                    $this->controller->group($notificationBody);
                    return;
                }
                $this->showBadRequestMessage();
                break;
            case '/api/message/topic':
                if ($this->requestMethod == 'POST') {
                    $notificationBody = [
                        'topic' => self::$data->topic ?? null,
                        'message' => self::$data->message ?? null,
                        'title' => self::$data->title ?? null,
                        'type' => self::$data->type ?? null
                    ];
                    $this->controller->topic($notificationBody);
                    return;
                }
                $this->showBadRequestMessage();
                break;

            case '/api/message/condition_topic':
                if ($this->requestMethod == 'POST') {
                    $notificationBody = [
                        'condition' => self::$data->condition ?? null,
                        'content' => self::$data->message ?? null,
                        'title' => self::$data->title ?? null,
                        'type' => self::$data->type ?? null
                    ];
                    $this->controller->topic($notificationBody, true);
                    return;
                }
                $this->showBadRequestMessage();
                break;
            default:
                break;
        }
    }

    private function showBadRequestMessage()
    {
        http_response_code(405);
        echo json_encode(array("status" => 405, "message" => "Use appropriate method for this action"));
    }

    private function showNotAuthenticatedMessage()
    {
        http_response_code(401);
        echo json_encode(
            array("status" => 401,
                "message" => "You're not authorised for this action",
                "error" => Authentication::$error));
    }

    private function changePassword(string $method)
    {
        if ($method == 'POST') {
            $credentials = array(
                "username" => self::$data->username ?? null,
                "old_password" => self::$data->old_password ?? null,
                "new_password" => self::$data->new_password ?? null,
                "confirm_password" => self::$data->confirm_password ?? null
            );
            $this->controller->changePassword($credentials);
            return;
        }
        $this->showBadRequestMessage();
    }
}
