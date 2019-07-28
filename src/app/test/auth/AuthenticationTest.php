<?php

namespace App\test\auth;

use App\auth\Authentication;
use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;
class AuthenticationTest extends TestCase
{

    protected $token;

    protected function setUp(): void
    {
        $env = Dotenv::create(__DIR__ . '/../../../Portal/');
        $env->load();
        $this->token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9pbmZvcmRhc2doLmNvbSIsImF1ZCI6Imh0dHA6XC9cL2luZm9yZGFzZ2guY29tIiwiaWF0IjoxNTY0MjQ5NzcyLCJuYmYiOjE1NjQyNDk3NzIsImRhdGEiOnsiaWQiOi0xLCJmdWxsX25hbWUiOiJ0ZXN0X25hbWUiLCJ1c2VybmFtZSI6InRlc3RfdXNlcm5hbWUiLCJpbWFnZSI6InRlc3RfaW1hZ2UiLCJsZXZlbCI6InRlc3RfbGV2ZWwifX0.hDm_nDwvDyv7FTi8ZzsJjB0shYzaW7VliqdYObV8H7A";
    }

    public function testIsJWTTokenValid()
    {
        $expected = Authentication::isJWTTokenValid($this->token);
        self::assertNull(Authentication::$error);
        self::assertEquals($expected, true);
    }

    public function testGetDecodedData()
    {
        $expected = Authentication::isJWTTokenValid($this->token);
        self::assertNull(Authentication::$error);
        self::assertEquals($expected, true);
        self::assertEquals(Authentication::getDecodedData()['id'], -1);
        self::assertEquals(Authentication::getDecodedData()['full_name'], 'test_name');
        self::assertEquals(Authentication::getDecodedData()['username'], 'test_username');
        self::assertEquals(Authentication::getDecodedData()['image'], 'test_image');
        self::assertEquals(Authentication::getDecodedData()['level'], 'test_level');
    }

    public function testEncodeJWTToken()
    {
        $jwt = array(
            "iss" => Authentication::$jwt_package['iss'],
            "aud" => Authentication::$jwt_package['aud'],
            "iat" => Authentication::$jwt_package['iat'],
            "nbf" => Authentication::$jwt_package['nbf'],
            "key" => getenv('JWT_KEY')
        );
        $user_data = array(
            "id" => -1,
            "full_name" => 'test_name',
            "username" => 'test_username',
            "image" => 'test_image',
            "level" => 'test_level'
        );

        $token = Authentication::encodeJWTToken($jwt, $user_data);
        self::assertNotNull($token);
    }
}
