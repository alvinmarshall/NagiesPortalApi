<?php


namespace App\common\utils;


class DirectoryUtils
{
    /**
     * @param $path
     */
    public static function createDir($path){
       if (!file_exists($path)){
           mkdir($path,0777,true);
       }
   }
}