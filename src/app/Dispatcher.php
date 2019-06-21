<?php

namespace App;
use App\data\action\Actions;
use App\routes\Request;
use App\routes\Router;

class Dispatcher
{
    private $req;

    /**
     * Dispatcher constructor.
     */
    public function __construct()
    {
        $this->req = new Request();
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
            case "instructors":
                $action->setInstructorAction();
                break;
            default:
                null;
        }
    }

}