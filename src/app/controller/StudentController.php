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
            'content' => $data->content ?? null
        ];
        if ($model->sendComplaints($complaint_data)) {
            $model->output['status'] = 200;
            $model->output['id'] = $model->id;
            $model->output['errors'] = $model->error;
            echo json_encode($model->output);
        } else {
            $model->output['status'] = 400;
            $model->output['errors'] = $model->error;
            echo json_encode($model->output);
        }
    }

    function index()
    {
        $model = new Students($this->conn);
        $results = $model->get();
        $results->execute();
        $num = $results->rowCount();
        $model->output['status'] = 200;
        $model->output['message'] = "Students Records";
        $model->output['count'] = $num;
        $model->output['students'] = [];
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
                array_push($model->output['students'], $student_item);
            }
            echo json_encode($model->output);
        }
    }

    /**
     * @param int $page
     * @param int $from
     * @param int $to
     */
    function paginateStudent(int $page = 1, int $from = 0, int $to = 5)
    {
        $model = new Students($this->conn);
        $results = $model->getWithPaginate($from, $to);
        $results->execute();
        $num = $results->rowCount();
        $model->output['message'] = "Students Records";
        $model->output['count'] = $num;
        $model->output['students'] = [];
        $model->output['paging'] = [];
        $total_row = $model->getTotalStudent();
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
                array_push($model->output['students'], $student_item);
            }
            $page_url = "/api/students/";
            $pagination = $this->getPaginate($page, $total_row, $to, $page_url);
            $model->output['paging'] = $pagination;
            echo json_encode($model->output);
        }
    }

    /**
     * @param $id
     */
    function show($id)
    {
        $model = new Students($this->conn);
        $results = $model->getById($id);
        $results->execute();
        $num = $results->rowCount();
        $model->output['message'] = "Student Record";
        $model->output['count'] = $num;
        $model->output['students'] = array();
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
                array_push($model->output['students'], $student_item);
            }
            echo json_encode($model->output);
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
        $model->output['status'] = 200;
        $model->output['message'] = "Student Report";
        $model->output['count'] = $num;
        $model->output['report'] = [];
        if ($num == 0) {
            $this->showNoDataMessage($model);
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

    private function showNoDataMessage(Students $std)
    {
        http_response_code(404);
        $std->output['status'] = 404;
        $std->output['message'] = "No Data Available";
        $std->output['count'] = 0;
        echo json_encode($std->output);
    }

    function getMessages()
    {
        $model = new Students($this->conn);
        $results = $model->getMessages();
        $results->execute();
        $num = $results->rowCount();
        $model->output['status'] = 200;
        $model->output['message'] = $num == 1 ? 'Available Message' : 'Available Messages';
        $model->output['count'] = $num;
        $model->output['messages'] = [];
        if ($num == 0) {
            $this->showNoDataMessage($model);
            return;
        }

        while ($row = $results->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            /**
             * @var string $Message_BY
             * @var string $Message_Level
             * @var string $M_Read
             * @var string $Message
             */
            $message_item = [
                'sender' => $Message_BY,
                'level' => $Message_Level,
                'content' => $Message,
                'read' => $M_Read
            ];
            array_push($model->output['messages'], $message_item);
        }
        echo json_encode($model->output);
    }

    function getProfile()
    {
        $model = new Students($this->conn);
        $results = $model->getStudentDetails();
        $results->execute();
        $num = $results->rowCount();
        $model->output['status'] = 200;
        $model->output['message'] = 'Student Profile';
        $model->output['count'] = $num;
        $model->output['studentProfile'] = [];
        if ($num == 0) {
            $this->showNoDataMessage($model);
            return;
        }

        while ($row = $results->fetch(PDO::FETCH_ASSOC)) {
            /**
             * @var string $Students_No
             * @var string $Students_Name
             * @var string $Dob
             * @var string $Gender
             * @var string $Admin_Date
             * @var string $Section_Name
             * @var string $Faculty_Name
             * @var string $Level_Name
             * @var string $Semester_Name
             * @var string $Index_No
             * @var string $Guardian_Name
             * @var string $Guardian_No
             * @var string $Image
             */
            extract($row);
            $profile_item = [
                "studentNo" => $Students_No,
                "studentName" => $Students_Name,
                "dob" => $Dob,
                "gender" => $Gender,
                "admissionDate" => $Admin_Date,
                "section" => $Section_Name,
                "faculty" => $Faculty_Name,
                "level" => $Level_Name,
                "semester" => $Semester_Name,
                "index" => $Index_No,
                "guardian" => $Guardian_Name,
                "contact" => $Guardian_No,
                "imageUrl" => $Image
            ];
            array_push($model->output['studentProfile'], $profile_item);
        }
        echo json_encode($model->output);
    }

}