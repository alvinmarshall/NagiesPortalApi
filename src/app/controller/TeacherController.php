<?php


namespace App\controller;


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
        $model->output['message'] = $num > 0 ? "Complaint Messages" : "Complaint Message";
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


}