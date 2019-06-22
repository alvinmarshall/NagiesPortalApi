<?php


namespace App\routes;


class Router
{
    public static function getRoute($id)
    {
        return $router = [
            "/api/students" => "StudentController@index",
            "/api/students/" => "StudentController@paginateStudent",
            "/api/students/page/$id" => "StudentController@paginateStudent",
            "/api/students/$id" => "StudentController@show",
            "/api/users/parent" => "UsersController@authenticateUser",
            "/api/users/teacher" => "UsersController@authenticateUser",
            "/api/teachers/assignment_pdf" => "TeacherController@assignmentFormat",
            "/api/teachers/assignment_image" => "TeacherController@assignmentFormat"
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
            "users" => 'App\controller\UsersController',
            "teachers" => 'App\controller\TeacherController'
        ];
        $instance = $controller[$path];
        return new $instance();
    }


}