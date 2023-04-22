<?php

namespace Test\Integration\User;

use GuzzleHttp\Exception\ClientException;
use Test\Integration\EndpointTest;

class RegisterUserEndpointTest extends EndpointTest
{
    const SUCCESS_EMAIL = 'some@email.com';

    public function test_registers_a_user()
    {
        $response = $this->client->request('POST', '/user', [
            'body' => json_encode([
                'name' => 'some name',
                'email' => self::SUCCESS_EMAIL,
                'password' => 'encodedPassword',
            ], JSON_THROW_ON_ERROR)
        ]);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_fails_if_no_email()
    {
        try {
            $response = $this->client->request('POST', '/user', [
                'body' => json_encode([
                    'password' => '2up3r23cr3t'
                ], JSON_THROW_ON_ERROR)
            ]);

            $responseCode = $response->getStatusCode();
        } catch (ClientException $exception) {
            $responseCode = $exception->getCode();
        }

        $this->assertEquals(400, $responseCode);
    }

    public function test_fails_if_email_already_in_use()
    {
        $this->client->request('POST', '/user', [
            'body' => json_encode(
                [
                'email' => self::SUCCESS_EMAIL,
                'password' => "anything",
                ], JSON_THROW_ON_ERROR)
        ]);
        try {
            $response = $this->client->request('POST', '/user', [
                'body' => json_encode([
                    'name' => 'other name',
                    'email' => self::SUCCESS_EMAIL,
                    'password' => 'IdonTk4r3.com'
                ], JSON_THROW_ON_ERROR)
            ]);
            $responseCode = $response->getStatusCode();
        } catch (ClientException $exception) {
            $responseCode = $exception->getCode();
        }

        $this->assertEquals(400, $responseCode);
    }

    public function test_fails_if_no_password()
    {
        try {
            $response = $this->client->request('POST', '/user', [
                'body' => json_encode(['email' => 'some@email.com'], JSON_THROW_ON_ERROR)
            ]);

            $responseCode = $response->getStatusCode();
        } catch (ClientException $exception) {
            $responseCode = $exception->getCode();
        }

        $this->assertEquals(400, $responseCode);
    }
    public function tearDown(): void
    {
        parent::tearDown();
        $this->deleteUser(self::SUCCESS_EMAIL);
    }
}
