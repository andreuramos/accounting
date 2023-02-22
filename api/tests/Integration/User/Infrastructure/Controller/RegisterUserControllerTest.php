<?php

namespace Test\Integration\User\Infrastructure\Controller;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use PHPUnit\Framework\TestCase;

class RegisterUserControllerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->client = new Client();
    }

    public function test_registers_a_user()
    {
        $response = $this->client->request('POST','http://nginx/register', [
            'body' => json_encode([
                'name' => 'some name',
                'email' => 'noexisting@email.com',
                'password' => 'encodedPassword',
            ], JSON_THROW_ON_ERROR)
        ]);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_fails_if_no_email()
    {
        try {
            $response = $this->client->request('POST', 'http://nginx/register', [
                'body' => json_encode([], JSON_THROW_ON_ERROR)
            ]);

            $responseCode = $response->getStatusCode();
        } catch (ClientException $exception) {
            $responseCode = $exception->getCode();
        }

        $this->assertEquals(400, $responseCode);
    }

    public function test_fails_if_email_already_in_use()
    {
        $this->markTestSkipped('WIP');
        try {
            $this->client->request('POST', 'http://nginx/register', [
                'body' => json_encode([
                    'name' => 'other name',
                    'email' => 'existing@email.com',
                    'password' => 'IdonTk4r3.com'
                ], JSON_THROW_ON_ERROR)
            ]);

            $this->fail('Allowed already existing email user to register');
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function tearDown(): void
    {
        parent::tearDown();

    }
}
