<?php /** @noinspection ALL */


namespace App\controller;


use App\auth\Authentication;
use App\common\AppConstant;
use App\common\utils\FileExtensionUtils;
use App\common\utils\PageUtils;
use App\common\utils\Validator;
use App\data\model\Students;
use App\resource\StudentResource;
use App\ServiceContainer;
use Exception;
use PDO;
use PDOStatement;

class StudentController extends BaseController
{
    private $model;

    public function __construct()
    {
        $this->model = ServiceContainer::inject()->get(AppConstant::IOC_STUDENTS_MODEL);
    }

    /**
     * StudentController constructor.
     * @param $data
     */

    function sendComplaints($data)
    {
        if (!isset($data)) return;
        $complaint_data = [
            'content' => $data->content ?? null
        ];
        if ($this->model->sendComplaints($complaint_data)) {
            $this->model->output['id'] = $this->model->id;
            $this->model->output['errors'] = $this->model->error;
            StudentResource::showData($this->model);
            $model = ServiceContainer::inject()->get(AppConstant::IOC_FCM_SERVICE);
            $msg = ['title' => 'Complaints from parent', 'content' => $complaint_data['content'], 'topic' => 'global'];
            $model->sendTopicMessaging($msg);
        } else {
            StudentResource::showBadRequest($this->model);
        }
    }

    function index()
    {
        $results = $this->model->get();
        $results->execute();
        $num = $results->rowCount();
        $this->model->output['message'] = "Students Records";
        $this->model->output['count'] = $num;
        $this->model->output['students'] = [];
        if ($num == 0) {
            StudentResource::showNoData($this->model);
            return;
        }
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
            array_push($this->model->output['students'], $student_item);
        }
        StudentResource::showData($this->model);
    }

    /**
     * @param int $page
     * @param int $from
     * @param int $to
     */
    function paginateStudent(int $page = 1, int $from = 0, int $to = 5)
    {
        $results = $this->model->getWithPaginate($from, $to);
        $results->execute();
        $num = $results->rowCount();
        $this->model->output['message'] = "Students Records";
        $this->model->output['count'] = $num;
        $this->model->output['students'] = [];
        $this->model->output['paging'] = [];
        $total_row = $this->model->getTotalStudent();
        if ($num == 0) {
            StudentResource::showNoData($this->model);
            return;
        }
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
            array_push($this->model->output['students'], $student_item);
        }
        $page_url = "/api/students/";
        $pagination = $this->getPaginate($page, $total_row, $to, $page_url);
        $this->model->output['paging'] = $pagination;
        StudentResource::showData($this->model);
    }

    /**
     * @param $id
     */
    function show($id)
    {
        $results = $this->model->getById($id);
        $results->execute();
        $num = $results->rowCount();
        $this->model->output['message'] = "Student Record";
        $this->model->output['count'] = $num;
        $this->model->output['students'] = array();
        if ($num == 0) {
            StudentResource::showNoData($this->model);
            return;
        }
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
            array_push($this->model->output['students'], $student_item);
        }
        StudentResource::showData($this->model);
    }

    /**
     * @param $format
     */
    function getReport($format)
    {
        $table = $format == 'pdf' ? 'report' : 'report_png';
        $results = $this->model->getStudentReport($table);
        $results->execute();
        $num = $results->rowCount();
        $this->model->output['message'] = "Student Report";
        $this->model->output['count'] = $num;
        $this->model->output['report'] = [];
        if ($num == 0) {
            StudentResource::showNoData($this->model);
            return;
        }
        while ($row = $results->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            /**
             * @var string $Students_Name
             * @var string $Teachers_Email
             * @var string $Report_Date
             * @var string $Report_File
             */
            $result_item = [
                'studentName' => $Students_Name,
                'teacherEmail' => $Teachers_Email,
                'fileUrl' => $Report_File,
                'format' => $format,
                'date' => $Report_Date
            ];
            array_push($this->model->output['report'], $result_item);
        }
        StudentResource::showData($this->model);
    }

    /**
     * @param $format
     */
    function assignmentFormat($format)
    {
        $results = null;
        $format = strtoupper($format);
        switch ($format) {
            case 'PDF':
                $results = $this->model->getAssignmentType('assignment', $format);
                $this->getAssignment($format, $results, $this->model);
                break;
            case 'JPEG':
                $results = $this->model->getAssignmentType('assignment_image', $format);
                $this->getAssignment($format, $results, $this->model);
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
        $model->output['message'] = 'Available Assignment ' . $format;
        $model->output['count'] = $num;
        $model->output['Assignment'] = [];

        if ($num == 0) {
            StudentResource::showNoData($model);
            return;
        }
        while ($row = $results->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            /**
             * @var string $Students_Name
             * @var string $Report_File
             * @var string $Report_Date
             * @var string $Teachers_Email
             **/
            $assigment_items = [
                "studentName" => $Students_Name,
                "teacherEmail" => $Teachers_Email,
                'fileUrl' => $Report_File,
                'format' => strtolower($format),
                'date' => $Report_Date
            ];
            array_push($model->output['Assignment'], $assigment_items);
        }
        StudentResource::showData($model);
    }

    /**
     *
     */
    function getMessages()
    {
        $results = $this->model->getMessages();
        $results->execute();
        $num = $results->rowCount();
        $this->model->output['message'] = $num == 1 ? 'Available Message' : 'Available Messages';
        $this->model->output['count'] = $num;
        $this->model->output['messages'] = [];
        if ($num == 0) {
            StudentResource::showNoData($this->model);
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
            array_push($this->model->output['messages'], $message_item);
        }
        StudentResource::showData($this->model);
    }

    function getProfile()
    {
        $results = $this->model->getStudentDetails();
        $results->execute();
        $num = $results->rowCount();
        $this->model->output['message'] = 'Student Profile';
        $this->model->output['count'] = $num;
        $this->model->output['studentProfile'] = [];
        if ($num == 0) {
            StudentResource::showNoData($this->model);
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
            array_push($this->model->output['studentProfile'], $profile_item);
        }
        StudentResource::showData($this->model);
    }

    /**
     * @param $input
     */
    function getFile($input)
    {
        $url = $input->fileUrl ?? '';
        if ($url == null) {
            http_response_code(400);
            return;
        }
        $path = $url;
        $content_type = FileExtensionUtils::getContentType($path);
        if ($content_type == null) return;
        header('Content-Disposition: attachment; filename=' . basename($path));
        header('Content-Transfer-Encoding: binary');
        header('Accept-Ranges: bytes');
        header('Content-Encoding: none');
        header("Content-Type: $content_type");
        readfile($path);
    }

    /**
     * @param $credentials
     * @throws Exception
     */
    function changePassword($credentials)
    {
        $id = Authentication::getDecodedData()['id'];
        $old_password = $credentials['old_password'] ?? null;
        $new_password = $credentials['new_password'] ?? null;
        $confirm_password = $credentials['confirm_password'] ?? null;
        $field = ['username', 'old_password', 'new_password', 'confirm_password'];
        $input = [$id, $old_password, $new_password, $confirm_password];
        if (!Validator::validateInput($field, $input)) {
            return;
        }
        $password_content = [
            "id" => $id,
            "old" => $old_password,
            "new" => $new_password,
            "confirm" => $confirm_password
        ];

        $model = ServiceContainer::inject()->get(AppConstant::IOC_USER_MODEL);
        $result = $model->changeUserPassword(AppConstant::TABLE_STUDENT, $password_content);
        if (!$result) {
            StudentResource::showBadRequest($model);
            return;
        }
        StudentResource::showData($model);
    }

    function getTeachers()
    {
        $result = $this->model->getClassTeachers();
        if ($result == null) return;
        $result->execute();
        $num = $result->rowCount();
        $this->model->output['message'] = 'Student Teachers';
        $this->model->output['count'] = $num;
        $this->model->output['studentTeachers'] = [];
        if ($num == 0) {
            StudentResource::showNoData($this->model);
            return;
        }
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            /** @var string $Teachers_No
             * @var string $Teachers_Name
             * @var string $Gender
             * @var string $Contact
             * @var string $Image
             */
            $teacher_item = [
                "uid" => $Teachers_No,
                "teacherName" => $Teachers_Name,
                "gender" => $Gender,
                "contact" => $Contact,
                "imageUrl" => $Image
            ];
            array_push($this->model->output['studentTeachers'], $teacher_item);
        }
        StudentResource::showData($this->model);
    }
}