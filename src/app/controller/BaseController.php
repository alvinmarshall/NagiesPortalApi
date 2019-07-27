<?php


namespace App\controller;


abstract class BaseController
{
    abstract function index();

    abstract function show($id);

    abstract function create();

    abstract function update($id);

    abstract function delete($id);
}