<?php


namespace App\resource;


use App\data\model\BaseModel;

abstract class Resource
{
    abstract static function showData(BaseModel $model);

    abstract static function showNoData(BaseModel $model);

    abstract static function showBadRequest(BaseModel $model);
}