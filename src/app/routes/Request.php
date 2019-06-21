<?php


namespace App\routes;


class Request
{

    private $uri;
    private $explode_url;
    private $model;
    private $home;
    private $header;
    private $data;
    private $token;

    /**
     * Request constructor.
     */
    public function __construct()
    {
        $this->uri = $_SERVER['REQUEST_URI'];
        $this->explode_url = explode('/', $this->uri);
        $this->home = $this->explode_url[1] ?? '';
        $this->model = $this->explode_url[2] ?? '';
        $this->header = apache_request_headers();
        $jwt = $this->header['Authorization'] ?? '';
        if (!empty($jwt)) {
            $this->token = explode("Bearer ", $jwt)[1];
        }
        $this->data = json_decode(file_get_contents('php://input'));
    }

    function getToken()
    {
        return $this->token ?? null;
    }

    public function getRequestUri()
    {
        return $this->uri;
    }

    public function requestMethod()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            return "POST";
        }
        return $_SERVER['REQUEST_METHOD'] == 'GET' ? "GET" : '';
    }

    public function getParams()
    {
        return intval(end($this->explode_url));
    }

    public function getModel()
    {
        return $this->model;
    }

    public function getHome()
    {
        return $this->home;
    }

    public function getInputData()
    {
        return $this->data ?? null;
    }

}