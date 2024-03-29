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
            "/api/students/assignment_pdf" => "StudentController@assignmentFormat",
            "/api/students/assignment_image" => "StudentController@assignmentFormat",
            "/api/students/messages" => "StudentController@getMessages",
            "/api/students/profile" => "StudentController@getProfile",
            "/api/students/download" => "StudentController@getFile",
            "/api/students/teachers" => "StudentController@getTeachers",
            "/api/students/circular" => "StudentController@getCircular",
            "/api/students/billing" => "StudentController@getBilling",
            "/api/students/announcement" => "StudentController@getAnnouncement",


            /**Users routes*/
            "/api/users/parent" => "UsersController@authenticateUser",
            "/api/users/teacher" => "UsersController@authenticateUser",
            "/api/students/change_password" => "StudentController@changePassword",
            "/api/teachers/change_password" => "TeacherController@changePassword",

            /**Teachers routes*/
            "/api/teachers/assignment_pdf" => "TeacherController@assignmentFormat",
            "/api/teachers/assignment_image" => "TeacherController@assignmentFormat",
            "/api/teachers/complaints" => "TeacherController@getComplaints",
            "/api/teachers/announcement" => "TeacherController@getAnnouncement",
            "/api/teachers/upload_assignment_pdf" => "TeacherController@sendAssignmentPDF",
            "/api/teachers/upload_assignment_image" => "TeacherController@sendAssignmentImage",
            "/api/teachers/upload_report_pdf" => "TeacherController@sendReportPDF",
            "/api/teachers/upload_report_image" => "TeacherController@sendReportImage",
            "/api/teachers/send_message" => "TeacherController@sendMessage",
            "/api/teachers/profile" => "TeacherController@getTeacherProfile",
            "/api/teachers/upload_circular" => "TeacherController@sendCircular",
            "/api/teachers/upload_billing" => "TeacherController@sendBilling",

            //firebase messaging route
            "/api/message/single" => "FCMController@single",
            "/api/message/group" => "FCMController@group",
            "/api/message/topic" => "FCMController@topic",
            "/api/message/condition_topic" => "FCMController@conditionTopic"

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
        if ($path == null) return null;
        $controller = [
            "students" => 'App\controller\StudentController',
            "users" => 'App\controller\UsersController',
            "teachers" => 'App\controller\TeacherController',
            'message' => 'App\controller\FCMController'
        ];
        $instance = $controller[$path];
        return new $instance();
    }


}