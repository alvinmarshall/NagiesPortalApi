<?php


namespace App\data;


interface IDataAccess
{
    function add();
    function get();
    function getById($id);
    function update($id);
    function delete($id);
}