<?php /** @noinspection PhpUndefinedMethodInspection */

namespace App\test\api;

use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

class StudentControllerTest extends TestCase
{
    protected $client;
    protected $token;

    protected function setUp()
    {
        $this->client = new Client([
            'base_uri' => 'http://localhost:81/api/'
        ]);
        $response = $this->client->post('users/parent', [
            'json' => [
                'username' => 'Creche',
                'password' => '1234'
            ]
        ]);
        $expected = json_decode($response->getBody(), true);
        $this->token = $expected['token'];
    }

    public function testGetProfile()
    {
        $response = $this->client->get('students/profile', [
            'headers' => ['Authorization' => "Bearer " . $this->token]
        ]);
        $expected = json_decode($response->getBody(), true);
        self::assertEquals(200, $response->getStatusCode());
        self::assertEquals($expected['type'], 'StudentProfile');
    }

    public function testIndex()
    {
        $response = $this->client->get('students', [
            'headers' => ['Authorization' => "Bearer " . $this->token]
        ]);
        $expected = json_decode($response->getBody(), true);
        self::assertEquals(200, $response->getStatusCode());
        self::assertEquals($expected['type'], 'Students');
    }

    public function testGetTeachers()
    {
        $response = $this->client->get('students/teachers', [
            'headers' => ['Authorization' => "Bearer " . $this->token]
        ]);
        self::assertEquals(200, $response->getStatusCode());
    }

    public function testGetReport()
    {
        $response = $this->client->get('students/assignment_pdf', [
            'headers' => ['Authorization' => "Bearer " . $this->token]
        ]);
        self::assertEquals(200, $response->getStatusCode());
    }

    public function testSendComplaints()
    {
        $response = $this->client->post('students/complaints', [
            'headers' => ['Authorization' => "Bearer " . $this->token],
            'json' => [
                'message' => 'unit testing'
            ]
        ]);
        self::assertEquals(200, $response->getStatusCode());
    }

    public function testShow()
    {
        $response = $this->client->get('students/1', [
            'headers' => ['Authorization' => "Bearer " . $this->token]
        ]);
        $expected = json_decode($response->getBody(), true);
        self::assertEquals(200, $response->getStatusCode());
        self::assertEquals($expected['type'], 'Students');
    }

    public function testGetMessages()
    {
        $response = $this->client->get('students/messages', [
            'headers' => ['Authorization' => "Bearer " . $this->token]
        ]);
        $expected = json_decode($response->getBody(), true);
        self::assertEquals(200, $response->getStatusCode());
        self::assertEquals($expected['type'], 'Messages');
    }

    public function testChangePassword()
    {
        $response = $this->client->post('students/change_password', [
            'headers' => ['Authorization' => "Bearer " . $this->token],
            'json' => [
                'old_password' => '1234',
                'new_password' => '1234',
                'confirm_password' => '1234'
            ]
        ]);
        $expected = json_decode($response->getBody(), true);
        self::assertEquals(200, $response->getStatusCode());
        self::assertEquals($expected['message'], 'Change password action successful');
    }
}
