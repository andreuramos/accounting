<?php

namespace Test\Integration\User;

use Test\Integration\EndpointTest;

class RefreshTokenEndpointTest extends EndpointTest
{
    public function test_refresh_with_no_params_returns_400()
    {
        $response = $this->client->post('refresh');

        $this->markTestIncomplete("Needs to receive a 400 instead of a 500");
        $this->assertEquals(400, $response->getStatusCode());
    }

    public function test_not_a_jwt_token_returns_401()
    {
        $response = $this->client->post('refresh', [
            'body' => json_encode([
                'refresh_token' => "Notevenajwt"
            ], JSON_THROW_ON_ERROR)
        ]);

        $this->assertEquals(401, $response->getStatusCode());
    }

    public function test_valid_jwt_returns_new_auth_and_refresh()
    {
        $this->registerUser($this->email, "correctPassword");
        $loginResponse = $this->client->post('login', [
            'body' => json_encode([
                'email' => $this->email,
                'password' => "correctPassword"
            ], JSON_THROW_ON_ERROR)
        ]);
        $loginContent = json_decode($loginResponse->getBody()->getContents(), true);
        $refreshToken = $loginContent['refresh'];

        $response = $this->client->post('refresh', [
            'body' => json_encode([
                'refresh_token' => $refreshToken
            ], JSON_THROW_ON_ERROR)
        ]);

        $this->assertEquals(200, $response->getStatusCode());
        $responseContent = json_decode($response->getBody()->getContents(), true);
        $this->assertArrayHasKey('token', $responseContent);
        $this->assertArrayHasKey('refresh', $responseContent);
    }

    public function tearDown(): void
    {
        parent::tearDown();
        $this->deleteUser($this->email);
    }
}
