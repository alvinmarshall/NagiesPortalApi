<?php

namespace App;

use App\common\AppConstant;
use App\data\action\Actions;
use App\routes\Router;
use Exception;

class Dispatcher
{
    private $req;

    /**
     * Dispatcher constructor.
     * @throws Exception
     */
    public function __construct()
    {
        $this->req = ServiceContainer::inject()->get(AppConstant::IOC_REQUEST);
    }

    /**start web service*/
    public function dispatch()
    {
        if (Router::validateRoute(Router::getRoute($this->req->getParams()), $this->req->getRequestUri())) {
            $controller = Router::attachController($this->req->getModel());
            if ($controller == null) return;
            $actions = new Actions(
                $this->req->getRequestUri(),
                $this->req->getParams(),
                $controller, $this->req->requestMethod()
            );
            $actions::$data = $this->req->getInputData();
            $actions::$token = $this->req->getToken();
            $actions::$userType = $this->req->getUserType();
            $actions->pageNo = $this->req->getPage();
            $this->setAction($this->req->getModel(), $actions);
        }
    }

    private function setAction($model, Actions $action)
    {
        switch ($model) {
            case "students":
                $action->setStudentAction();
                break;
            case "users":
                $action->setUserAction();
                break;
            case "teachers":
                $action->setTeachersAction();
                break;
            case "message":
                $action->setFirebaseMessagingAction();
                break;
            default:
                null;
        }
    }

}