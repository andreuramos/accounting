<?php

namespace Test\Integration\User;

use GuzzleHttp\Exception\RequestException;
use Test\Integration\EndpointTest;

class LoginEndpointTest extends EndpointTest
{
    private const EXISTING_EMAIL = "existing@email.com";
    private const EXISTING_EMAIL2 = "another@existing.net";
    private const EXISTING_EMAIL3 = "last@email.org";

    public function test_status_200_if_correct_credentials(): void
    {
        try {
            $this->registerUser(self::EXISTING_EMAIL, "correctPassword");
            $response = $this->client->post('/login', [
                'body' => json_encode([
                    'email' => self::EXISTING_EMAIL,
                    'password' => "correctPassword"
                ], JSON_THROW_ON_ERROR)
            ]);
            $this->assertEquals(200, $response->getStatusCode());
        } catch (RequestException $e) {
            $this->fail($e->getMessage());
        }
    }

    public function test_returns_tokens_if_correct_credentials(): void
    {
        try {
            $this->registerUser(self::EXISTING_EMAIL2, "correctPassword");
            $response = $this->client->post('/login', [
                'body' => json_encode([
                    'email' => self::EXISTING_EMAIL2,
                    'password' => "correctPassword"
                ], JSON_THROW_ON_ERROR)
            ]);
            $responseData = json_decode($response->getBody(), true);
            $this->assertNotEmpty($responseData['token']);
        } catch (RequestException $e) {
            $this->fail($e->getMessage());
        }
    }

    public function test_fails_if_wrong_credentials(): void
    {
        try {
            $this->registerUser(self::EXISTING_EMAIL3, "correctPassword");
            $response = $this->client->post('/login', [
                'body' => json_encode([
                    'email' => self::EXISTING_EMAIL3,
                    'password' => "wrongPassword"
                ], JSON_THROW_ON_ERROR)
            ]);
            $this->assertEquals(401, $response->getStatusCode());
        } catch (RequestException $e) {
            $this->assertEquals(401, $e->getCode());
        }
    }

    public function tearDown(): void
    {
        parent::tearDown();
        $this->pdo->query('DELETE FROM user WHERE email="' . self::EXISTING_EMAIL . '";');
        $this->pdo->query('DELETE FROM user WHERE email="' . self::EXISTING_EMAIL2 . '";');
        $this->pdo->query('DELETE FROM user WHERE email="' . self::EXISTING_EMAIL3 . '";');
    }
}
