<?php

namespace App\test\utils;

use App\common\utils\Validator;
use PHPUnit\Framework\TestCase;

class ValidatorTest extends TestCase
{

    public function testSingleInput_IN_HTML_FORM()
    {
        $expected = Validator::singleInput('<p>Web Page Data</p>');
        self::assertEquals($expected, 'Web Page Data');
    }

    public function testValidateInput_With_Input()
    {
        $field = ['field'];
        $input = ['input'];
        self::assertTrue(Validator::validateInput($field, $input));
    }

    public function testValidateInput_No_Input()
    {
        $field = ['field'];
        $input = [''];
        self::assertFalse(Validator::validateInput($field, $input));
    }

    public function testValidateInput_Field_Not_Equal_To_Input()
    {
        $field = ['field', ''];
        $input = ['input'];
        self::assertFalse(Validator::validateInput($field, $input));
    }
}
