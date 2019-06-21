<?php


namespace App\routes;


class Router
{
    public static function getRoute($id)
    {
        return $router = [
            "/api/students" => "StudentController@index",
            "/api/students/$id" => "StudentController@show",
            "api/students/add" => "StudentController@create",
            "/api/users" => "UsersController@index",
            "/api/users/login" => "UsersController@authenticateUser",
            "api/instructors" => "InstructorsController@index",
            "api/instructors/$id" => "InstructorsController@show",
            "api/instructors/add" => "InstructorsController@create"
        ];

    }

    /**
     * @param $routes
     * @param $uri
     * @return bool
     */
    public static function validateRoute($routes, $uri)
    {
        if (isset($routes[$uri])) {
            return true;
        }
        return false;
    }


    /**
     * @param $path
     * @return object
     */
    public static function attachController($path)
    {
        $controller = [
            "students" => 'App\controller\StudentController',
            "users" => 'ComposerIncludeFiles\app\controller\UsersController',
            "instructors" => 'ComposerIncludeFiles\app\controller\InstructorsController'
        ];
        $instance = $controller[$path];
        return new $instance();
    }


}