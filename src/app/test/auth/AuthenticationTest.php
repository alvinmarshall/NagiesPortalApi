<?php

namespace App\test\auth;

use App\auth\Authentication;
use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;

class AuthenticationTest extends TestCase
{

    protected $token;
    protected $fakeToken;

    protected function setUp(): void
    {
        $env = Dotenv::create(__DIR__ . '/../../../../');
        $env->load();
        $this->fakeToken = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9pbmZvcmRhc2doLmNvbSIsImF1ZCI6Imh0dHA6XC9cL2luZm9yZGFzZ2guY29tIiwiaWF0IjoxNTY1NDc4MzE5LCJuYmYiOjE1NjU0NzgzMTksImRhdGEiOnsiaWQiOi0xLCJmdWxsX25hbWUiOiJ0ZXN0X25hbWUiLCJ1c2VybmFtZSI6InRlc3RfdXNlcm5hbWUiLCJpbWFnZSI6InRlc3RfaW1hZ2UiLCJsZXZlbCI6InRlc3RfbGV2ZWwiLCJyb2xlIjoidGVzdF9yb2xlIn19.el0HHHuMkcAuVoGbP2BLrr96hgpGCjOhoii7rxxyHQi";
        $this->token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9pbmZvcmRhc2doLmNvbSIsImF1ZCI6Imh0dHA6XC9cL2luZm9yZGFzZ2guY29tIiwiaWF0IjoxNTY1NDc4MzE5LCJuYmYiOjE1NjU0NzgzMTksImRhdGEiOnsiaWQiOi0xLCJmdWxsX25hbWUiOiJ0ZXN0X25hbWUiLCJ1c2VybmFtZSI6InRlc3RfdXNlcm5hbWUiLCJpbWFnZSI6InRlc3RfaW1hZ2UiLCJsZXZlbCI6InRlc3RfbGV2ZWwiLCJyb2xlIjoidGVzdF9yb2xlIn19.el0HHHuMkcAuVoGbP2BLrr96hgpGCjOhoii7rxxyHQI";
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
        self::assertEquals(Authentication::getDecodedData()['role'], 'test_role');

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
            "level" => 'test_level',
            'role' => 'test_role'
        );

        $token = Authentication::encodeJWTToken($jwt, $user_data);
        self::assertNotNull($token);
    }

    function testTokenSignatureVerificationException(){
        Authentication::isJWTTokenValid($this->fakeToken);
        $expected =  Authentication::$error;
        $actual = 'Signature verification failed';
        self::assertEquals($expected,$actual);
    }


}
