<?php


namespace App\common\utils;


class FileExtensionUtils
{
    public static function getContentType($file)
    {
        $item = pathinfo($file);
        $extension = $item['extension'];
        $content_type = null;
        switch ($extension) {
            case 'pdf':
                $content_type = 'application/pdf';
                break;
            case 'jpeg':
                $content_type = 'image/jpeg';
                break;
            case 'jpg':
                $content_type = 'image/jpeg';
                break;
            case 'png':
                $content_type = 'image/png';
                break;
            default:
                break;
        }
        return $content_type;
    }
}