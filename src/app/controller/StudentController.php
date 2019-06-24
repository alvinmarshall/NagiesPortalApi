<?php


namespace App\controller;


use App\common\utils\PageUtils;
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
        $std->output['students'] = [];
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
                    "guardianContact" => $Guardian_No,
                    "image" => $Image
                ];
                array_push($std->output['students'], $student_item);
            }
            echo json_encode($std->output);
        }
    }

    /**
     * @param int $page
     * @param int $from
     * @param int $to
     */
    function paginateStudent(int $page = 1, int $from = 0, int $to = 5)
    {
        $std = new Students($this->conn);
        $results = $std->getWithPaginate($from, $to);
        $results->execute();
        $num = $results->rowCount();
        $std->output['message'] = "Students Records";
        $std->output['count'] = $num;
        $std->output['students'] = [];
        $std->output['paging'] = [];
        $total_row = $std->getTotalStudent();
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
                    "guardianContact" => $Guardian_No,
                    "image" => $Image
                ];
                array_push($std->output['students'], $student_item);
            }
            $page_url = "/api/students/";
            $pagination = $this->getPaginate($page, $total_row, $to, $page_url);
            $std->output['paging'] = $pagination;
            echo json_encode($std->output);
        }
    }

    function show($id)
    {
        $std = new Students($this->conn);
        $results = $std->getById($id);
        $results->execute();
        $num = $results->rowCount();
        $std->output['message'] = "Student Record";
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
                    "guardianContact" => $Guardian_No,
                    "image" => $Image
                ];
                array_push($std->output['students'], $student_item);
            }


            echo json_encode($std->output);
        }

    }

    function getReport($format){
        $model = new Students($this->conn);
        $table = $format == 'pdf' ? 'report' : 'report_png';
        $results = $model->getStudentReport($table);
        $results->execute();
        $num = $results->rowCount();
        $model->output['message'] = "Student Report";
        $model->output['count'] = $num;
        $model->output['report'] = [];
        if ($num == 0) {
            echo json_encode($model->output);
            return;
        }
        while ($row = $results->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            /**
             * @var string $Students_Name
             * @var string $Students_No
             * @var string $Teachers_Email
             * @var string $Report_Date
             * @var string $Report_File
            */
            $result_item = [
                'studentNo' => $Students_No,
                'studentName' => $Students_Name,
                'teacherEmail' => $Teachers_Email,
                'fileUrl' => $Report_File,
                'format' => $format,
                'date' => $Report_Date
            ];
            array_push($model->output['report'],$result_item);
        }
        echo json_encode($model->output);

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
        echo "Student Controller delete $id";
    }

    private function getPaginate(int $page, int $total_rows, int $records_per_page, string $page_url)
    {
        $paginate = new PageUtils();
        return $paginate->setPagination($page, $total_rows, $records_per_page, $page_url);
    }


}