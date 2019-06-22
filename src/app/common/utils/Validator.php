<?php


namespace App\common\utils;


class Validator
{
    public static function singleInput($input, $field='')
    {
        $inp = htmlspecialchars(strip_tags($input));
        if (!empty($inp)) {
            return $inp;
        }
        return $inp;
    }
}