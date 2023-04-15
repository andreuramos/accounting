<?php

namespace Test\Integration\User;

use GuzzleHttp\Exception\RequestException;
use Test\Integration\EndpointTest;

class LoginEndpointTest extends EndpointTest
{
    const EXISTING_EMAIL = "existing@email.com";

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
        $this->markTestIncomplete();
    }

    public function test_fails_if_wrong_credentials(): void
    {
        $this->markTestIncomplete();
    }

    private function registerUser(string $email, string $password)
    {
        $this->client->post('/register',[
            'body' => json_encode([
                'email' => $email,
                'password' => $password
            ], JSON_THROW_ON_ERROR)
        ]);
    }

    public function tearDown(): void
    {
        parent::tearDown();
        $pdo = $this->container->get(\PDO::class);
        $pdo->query('DELETE FROM user WHERE email="' . self::EXISTING_EMAIL . '";');
    }
}
