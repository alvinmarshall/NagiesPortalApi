<?php


namespace App\controller;


use App\data\model\Students;
use PDO;

class StudentController extends BaseController
{
    /**
     * StudentController constructor.
     */


    function index()
    {
        $std = new Students($this->conn);
        $results = $std->get();
        $results->execute();
        $num = $results->rowCount();
        $std->output['message'] = "Students Records";
        $std->output['count'] = $num;
        $std->output['students'] = array();
        if ($num > 0) {
            while ($row = $results->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                /**
                 * @var number
                 * @var string $id
                 * @var string $Students_No
                 * @var string $Students_Name
                 * @var string $Dob
                 * @var string $Gender
                 * @var string $dob
                 * @var string $Age
                 * @var string $Section_Name
                 * @var string $Faculty_Name
                 * @var string $Semester_Name
                 * @var string $Guardian_Name
                 * @var string $Guardian_No
                 * @var string $Admin_Date
                 * @var string $Image
                 * @var string $Index_No
                 * @var string $Level_Name
                 */
                $student_item = [
                    "id" => $id,
                    "refNo" => $Students_No,
                    "name" => $Students_Name,
                    "indexNo" => $Index_No,
                    "dob" => $Dob,
                    "gender" => $Gender,
                    "admissionDate" => $Admin_Date,
                    "age" => $Age,
                    "sectionName" => $Section_Name,
                    "facultyName" => $Faculty_Name,
                    "semester" => $Semester_Name,
                    "level" => $Level_Name,
                    "guardianName" => $Guardian_Name,
                    "guardian contact" => $Guardian_No,
                    "image" => $Image
                ];
                array_push($std->output['students'], $student_item);
            }
            echo json_encode($std->output);
        }
    }

    function show($id)
    {
        echo "Student Controller show $id";
    }

    function create()
    {
        echo 'Student Controller create';
    }

    function update($id)
    {
        echo "Student Controller update $id";
    }

    function delete($id)
    {
        // TODO: Implement delete() method.
    }


}