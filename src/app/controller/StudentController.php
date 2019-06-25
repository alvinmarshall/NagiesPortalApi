<?php


namespace App\controller;


use App\common\utils\PageUtils;
use App\data\model\Students;
use PDO;
use PDOStatement;

class StudentController extends BaseController
{
    /**
     * StudentController constructor.
     * @param $data
     */

    function sendComplaints($data)
    {
        if (!isset($data)) return;
        $model = new Students($this->conn);
        $complaint_data = [
            'sender' => $data->sender ?? null,
            'name' => $data->name ?? null,
            'content' => $data->content ?? null,
            'level' => $data->level ?? null,
            'date' => $data->date ?? null,
            'guardian' => $data->guardian ?? null,
            'contact' => $data->contact ?? null
        ];
        if ($model->sendComplaints($complaint_data)) {
            $model->output['id'] = $model->id;
            $model->output['errors'] = $model->error;
            echo json_encode($model->output);
        } else {
            $model->output['errors'] = $model->error;
            echo json_encode($model->output);
        }
    }

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

    /**
     * @param $id
     */
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

    /**
     * @param $format
     */
    function getReport($format)
    {
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
        while ($row = $results->fetch(PDO::FETCH_ASSOC)) {
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
            array_push($model->output['report'], $result_item);
        }
        echo json_encode($model->output);

    }

    /**
     * @param $format
     */
    function assignmentFormat($format)
    {
        $model = new Students($this->conn);
        $results = null;
        $format = strtoupper($format);
        switch ($format) {
            case 'PDF':
                $results = $model->getAssignmentType('assignment', $format);
                $this->getAssignment($format, $results, $model);
                break;
            case 'JPEG':
                $results = $model->getAssignmentType('assignment_image', $format);
                $this->getAssignment($format, $results, $model);
                break;
            default:
                null;
        }

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

    /**
     * @param int $page
     * @param int $total_rows
     * @param int $records_per_page
     * @param string $page_url
     * @return array
     */
    private function getPaginate(int $page, int $total_rows, int $records_per_page, string $page_url)
    {
        $paginate = new PageUtils();
        return $paginate->setPagination($page, $total_rows, $records_per_page, $page_url);
    }

    /**
     * @param $format
     * @param PDOStatement $results
     * @param Students $model
     */
    private function getAssignment($format, PDOStatement $results, Students $model)
    {
        $results->execute();
        $num = $results->rowCount();

        $model->output['status'] = 200;
        $model->output['message'] = 'Available Assignment ' . $format;
        $model->output['count'] = $num;
        $model->output['Assignment' . $format] = [];

        if ($num == 0) {
            $this->showNoDataMessage($model);
            return;
        }
        if ($num > 0) {
            while ($row = $results->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                /**
                 * @var string $id
                 * @var string $Students_No
                 * @var string $Students_Name
                 * @var string $Report_File
                 * @var string $Report_Date
                 * @var string $Teachers_Email
                 **/
                $assigment_items = [
                    "id" => $id,
                    "studentNo" => $Students_No,
                    "studentName" => $Students_Name,
                    "teacherEmail" => $Teachers_Email,
                    "reportFile" => $Report_File,
                    "reportDate" => $Report_Date
                ];
                array_push($model->output['Assignment' . $format], $assigment_items);
            }
            echo json_encode($model->output);
        }
    }

    private function showNoDataMessage(Students $tch)
    {
        http_response_code(404);
        $tch->output['status'] = 404;
        $tch->output['message'] = "No Data Available";
        $tch->output['count'] = 0;
        echo json_encode($tch->output);
    }

    function getMessages(){
        $model = new Students($this->conn);
        $results = $model->getMessages();
        $results->execute();
        $num = $results->rowCount();
        $model->output['message'] = $num == 1 ? 'Available Message' : 'Available Messages';
        $model->output['count'] = $num;
        $model->output['messages'] = [];
        if ($num == 0){
            $this->showNoDataMessage($model);
            return;
        }

        while ($row = $results->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            /**
             * @var string $Message_By
             * @var string $Message_Level
             * @var string $M_Read
             * @var string $Message
             */
            $message_item = [
                'sender' => $Message_By,
                'level' => $Message_Level,
                'content' => $Message,
                'status' => $M_Read
            ];
            array_push($model->output['messages'],$message_item);
        }
        echo json_encode($model->output);
    }

}