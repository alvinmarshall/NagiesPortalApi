<?php


namespace App\data\model;


abstract class BaseModel
{
    protected $dbTable;
    public $error;
    public $output = array();
    public $id;

}