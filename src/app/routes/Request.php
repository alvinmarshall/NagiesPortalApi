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
    private $user;
    private $page;

    /**
     * Request constructor.
     */
    public function __construct()
    {
        $this->uri = $_SERVER['REQUEST_URI'];
        $this->explode_url = explode('/', $this->uri);
        $this->home = $this->explode_url[1] ?? '';
        $this->model = $this->explode_url[2] ?? '';
        $this->user = $this->explode_url[3] ?? '';
        $this->page = $this->explode_url[4] ?? '';
        $this->header = apache_request_headers();
        $jwt = $this->header['Authorization'] ?? '';
        if (!empty($jwt)) {
            if (strpos($jwt, "key") !== true) {
                /* firebase key, is been handled in sendMessage function in user controller*/
                $this->token = null;
            }
            $this->token = explode("Bearer ", $jwt)[1];
        }
        $this->data = json_decode(file_get_contents('php://input'));
    }

    function getToken()
    {
        return $this->token ?? null;
    }

    function getUserType()
    {
        return $this->user;
    }

    public function getRequestUri()
    {
        if ($this->uri == '/' || $this->uri == '/api' || $this->uri == '/api/') {
            $port = $_SERVER['SERVER_PORT'];
            echo "Server Running on port $port";
        }
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

    public function getPage()
    {
        return intval($this->page);
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