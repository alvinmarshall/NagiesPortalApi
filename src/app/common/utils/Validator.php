<?php


namespace App\common\utils;


class Validator
{
    /**
     * @param $input
     * @return string
     */
    public static function singleInput($input)
    {
        $inp = htmlspecialchars(strip_tags($input));
        if (!empty($inp)) {
            return $inp;
        }
        return $inp;
    }

    /**
     * @param $fields
     * @param $inputs
     * @return bool
     */
    public static function validateInput($fields, $inputs): bool
    {
        $output = [];
        $error = [];
        foreach ($inputs as $input => $data) {
            if (empty($data)) {
                $output['message'] = "fields not set";
                $error[$fields[$input]] = "can't be empty";
            }
        }
        $output['errors'] = $error;
        $output['count'] = count($output['errors']);
        if (array_count_values($output['errors'])) {
            http_response_code(400);
            $output['status'] = 400;
            echo json_encode($output);
            return false;
        }
        return true;
    }
}