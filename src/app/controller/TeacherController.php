<?php


namespace App\controller;


use App\common\utils\DirectoryUtils;
use App\data\model\Teachers;
use PDO;
use PDOStatement;

class TeacherController extends BaseController
{

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

    function sendAssignment()
    {
        $upload_dir = './students/uploads/';
        if (isset($_FILES['pdf']['name'])) {
            DirectoryUtils::createDir($upload_dir);
            $file_info = pathinfo($_FILES['pdf']['name']);
            $file_name = $file_info['filename'];
            $extension = $file_info['extension'];
            $destination = $upload_dir . $file_name . '.' . $extension;
            $this->prepareToUploadFile('pdf', $destination, $upload_dir, $file_name, $extension);

        } elseif (isset($_FILES['image']['name'])) {
            DirectoryUtils::createDir($upload_dir);
            $file_info = pathinfo($_FILES['image']['name']);
            $file_name = $file_info['filename'];
            $extension = $file_info['extension'];
            $destination = $upload_dir . $file_name . '.' . $extension;
            $this->prepareToUploadFile('image', $destination, $upload_dir, $file_name, $extension);

        } else {
            echo json_encode(array('message' => 'No file to upload'));
        }

    }

    function getMessages()
    {
        $model = new Teachers($this->conn);
        $results = $model->getMessages();
        $results->execute();
        $num = $results->rowCount();
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
            array_push($model->output['messages'], $message_item);
        }
        echo json_encode($model->output);
    }

    /**
     * @param $format
     */
    function assignmentFormat($format)
    {
        $model = new Teachers($this->conn);
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

    function getComplaints()
    {
        $model = new Teachers($this->conn);
        $results = $model->getComplaints();
        $results->execute();
        $num = $results->rowCount();
        $model->output['message'] = $num == 1 ? "Complaint Message" : "Complaint Messages";
        $model->output['count'] = $num;
        $model->output['complaints'] = [];
        if ($num == 0) {
            $this->showNoDataMessage($model);
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
            array_push($model->output['complaints'], $complaint_item);
        }
        echo json_encode($model->output);
    }

    private function showNoDataMessage(Teachers $tch)
    {
        http_response_code(404);
        $tch->output['status'] = 404;
        $tch->output['message'] = "No Data Available";
        $tch->output['count'] = 0;
        echo json_encode($tch->output);
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

    /**
     * @param string $format
     * @param string $destination
     * @param string $upload_dir
     * @param $file_name
     * @param $extension
     */
    private function prepareToUploadFile(string $format, string $destination, string $upload_dir, $file_name, $extension): void
    {
        if (move_uploaded_file($_FILES[$format]['tmp_name'], $destination)) {
            $model = new Teachers($this->conn);
            $location = $upload_dir . $file_name . '.' . $extension;
            if ($model->sendAssignment($format, $location)) {
                $model->output['id'] = $model->id;
                $model->output['errors'] = $model->error;
                echo json_encode($model->output);
            } else {
                $model->output['errors'] = $model->error;
                echo json_encode($model->output);
            }

        } else {
            echo json_encode(array('message' => 'attempting to upload file failed'));
        }
    }


}