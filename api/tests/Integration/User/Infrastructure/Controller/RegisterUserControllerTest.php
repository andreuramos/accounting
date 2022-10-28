<?php

namespace Test\Integration\User\Infrastructure\Controller;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use PHPUnit\Framework\TestCase;

class RegisterUserControllerTest extends TestCase
{
    public function test_registers_a_user()
    {
        $client = new Client();

        $response = $client->request('POST','http://nginx/register', [
            'body' => json_encode([
                'name' => 'some name',
                'email' => 'existing@email.com',
                'password' => 'encodedPassword',
            ], JSON_THROW_ON_ERROR)
        ]);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_fails_if_no_email()
    {
        $client = new Client();

        try {
            $response = $client->request('POST', 'http://nginx/register', [
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
        $client = new Client();

        try {
            $client->request('POST', 'http://nginx/register', [
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
}
