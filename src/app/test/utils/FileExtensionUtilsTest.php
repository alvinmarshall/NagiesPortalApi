<?php

namespace App\test\utils;

use App\common\utils\FileExtensionUtils;
use PHPUnit\Framework\TestCase;

class FileExtensionUtilsTest extends TestCase
{

    public function testGetContentType_Format_JPEG()
    {
        $type = FileExtensionUtils::getContentType('test.jpg');
        self::assertEquals($type,'image/jpeg');
    }

    public function testGetContentType_Format_PDF()
    {
        $type = FileExtensionUtils::getContentType('test.pdf');
        self::assertEquals($type,'application/pdf');
    }

    public function testGetContentType_Format_PNG()
    {
        $type = FileExtensionUtils::getContentType('test.png');
        self::assertEquals($type,'image/png');
    }
}
