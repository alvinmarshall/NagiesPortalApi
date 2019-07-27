<?php


namespace App\resource;


class StudentResource extends Resource
{

    static function showData($model)
    {
        http_response_code(200);
        $model->output['status'] = 200;
        echo json_encode($model->output);
    }

    static function showNoData($model)
    {
        http_response_code(404);
        $model->output['status'] = 404;
        $model->output['message'] = "No Data Available";
        $model->output['count'] = 0;
        echo json_encode($model->output);
    }

    static function showBadRequest($model)
    {
        http_response_code(400);
        $model->output['status'] = 400;
        $model->output['errors'] = $model->error;
        echo json_encode($model->output);
    }
}