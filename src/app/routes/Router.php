<?php


namespace App\routes;


class Router
{
    public static function getRoute($id)
    {
        return $router = [
            /**Students routes*/
            "/api/students" => "StudentController@index",
            "/api/students/" => "StudentController@paginateStudent",
            "/api/students/page/$id" => "StudentController@paginateStudent",
            "/api/students/$id" => "StudentController@show",
            "/api/students/report_pdf" => "StudentController@getReport",
            "/api/students/report_image" => "StudentController@getReport",
            "/api/students/complaints" => "StudentController@sendComplaints",

            /**Users routes*/
            "/api/users/parent" => "UsersController@authenticateUser",
            "/api/users/teacher" => "UsersController@authenticateUser",

            /**Teachers routes*/
            "/api/teachers/assignment_pdf" => "TeacherController@assignmentFormat",
            "/api/teachers/assignment_image" => "TeacherController@assignmentFormat",
            "/api/teachers/complaints" => "TeacherController@getComplaints",
            "/api/teachers/messages" => "TeacherController@getMessages"

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