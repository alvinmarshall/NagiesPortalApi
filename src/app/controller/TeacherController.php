<?php /** @noinspection ALL */


namespace App\controller;


use App\common\AppConstant;
use App\common\utils\DirectoryUtils;
use App\data\model\Teachers;
use App\resource\TeacherResource;
use App\ServiceContainer;
use PDO;
use PDOStatement;

class TeacherController extends BaseController
{
    private $model;

    public function __construct()
    {
        $this->model = ServiceContainer::inject()->get(AppConstant::IOC_TEACHERS_MODEL);
    }

    function index()
    {
        // TODO: Implement index() method.
    }

    function show($id)
    {
        // TODO: Implement show() method.
    }

    function create()
    {
        // TODO: Implement create() method.
    }

    function update($id)
    {
        // TODO: Implement update() method.
    }

    function delete($id)
    {
        // TODO: Implement delete() method.
    }

    function uploadFile(string $dbTable)
    {
        $upload_dir = './students/uploads/';
        if (isset($_FILES['pdf']['name'])) {
            DirectoryUtils::createDir($upload_dir);
            $file_info = pathinfo($_FILES['pdf']['name']);
            $file_name = $file_info['filename'];
            $extension = $file_info['extension'];
            $destination = $upload_dir . $file_name . '.' . $extension;
            $this->prepareToUploadFile('pdf', $destination, $upload_dir, $file_name, $extension, $dbTable);

        } elseif (isset($_FILES['image']['name'])) {
            DirectoryUtils::createDir($upload_dir);
            $file_info = pathinfo($_FILES['image']['name']);
            $file_name = $file_info['filename'];
            $extension = $file_info['extension'];
            $destination = $upload_dir . $file_name . '.' . $extension;
            $this->prepareToUploadFile('image', $destination, $upload_dir, $file_name, $extension, $dbTable);

        } else {
            echo json_encode(array('message' => 'No file to upload'));
        }
    }

    function getMessages()
    {
        $results = $this->model->getMessages();
        $results->execute();
        $num = $results->rowCount();
        $this->model->output['message'] = $num == 1 ? 'Available Message' : 'Available Messages';
        $this->model->output['count'] = $num;
        $this->model->output['messages'] = [];
        if ($num == 0) {
            TeacherResource::showNoData($this->model);
            return;
        }

        while ($row = $results->fetch(PDO::FETCH_ASSOC)) {
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
            array_push($this->model->output['messages'], $message_item);
        }
        TeacherResource::showData($this->model);
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

    function getComplaints()
    {
        $results = $this->model->getComplaints();
        $results->execute();
        $num = $results->rowCount();
        $this->model->output['message'] = $num == 1 ? "Complaint Message" : "Complaint Messages";
        $this->model->output['count'] = $num;
        $this->model->output['complaints'] = [];
        if ($num == 0) {
            TeacherResource::showNoData($this->model);
            return;
        }
        while ($row = $results->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            /**
             * @var string $Students_No
             * @var string $Students_Name
             * @var string $Level_Name
             * @var string $Guardian_Name
             * @var string $Guardian_No
             * @var string $Message
             * @var string $Teachers_Name
             * @var string $Message_Date
             */
            $complaint_item = [
                "studentNo" => $Students_No,
                "studentName" => $Students_Name,
                "level" => $Level_Name,
                "guardianName" => $Guardian_Name,
                "guardianContact" => $Guardian_No,
                "teacherName" => $Teachers_Name,
                "message" => $Message,
                "date" => $Message_Date
            ];
            array_push($this->model->output['complaints'], $complaint_item);
        }
        TeacherResource::showData($this->model);
    }

    private function getAssignment($format, PDOStatement $results, Teachers $model)
    {
        $results->execute();
        $num = $results->rowCount();

        $model->output['status'] = 200;
        $model->output['message'] = 'Available Assignment ' . $format;
        $model->output['count'] = $num;
        $model->output['Assignment' . $format] = [];

        if ($num == 0) {
            TeacherResource::showNoData($model);
            return;
        }
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
        TeacherResource::showData($model);
    }

    /**
     * @param string $format
     * @param string $destination
     * @param string $upload_dir
     * @param $file_name
     * @param $extension
     * @param string $dbTable
     */
    private function prepareToUploadFile(
        string $format, string $destination,
        string $upload_dir, $file_name, $extension, string $dbTable
    ): void
    {
        if (move_uploaded_file($_FILES[$format]['tmp_name'], $destination)) {
            $location = $upload_dir . $file_name . '.' . $extension;
            if ($this->model->saveUploadFilePath($format, $location, $dbTable)) {
                $this->model->output['id'] = $this->model->id;
                $this->model->output['errors'] = $this->model->error;
                echo json_encode($this->model->output);
            } else {
                $this->model->output['errors'] = $this->model->error;
                echo json_encode($this->model->output);
            }
        } else {
            echo json_encode(array('message' => 'attempting to upload file failed'));
        }
    }
}