<?php

namespace Test\Integration\User;

use GuzzleHttp\Exception\RequestException;
use Test\Integration\EndpointTest;

class LoginEndpointTest extends EndpointTest
{
    public function test_status_200_if_correct_credentials(): void
    {
        try {
            $response = $this->client->post('/login', [
                'body' => json_encode([
                    'email' => "existing@email.com",
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
}
