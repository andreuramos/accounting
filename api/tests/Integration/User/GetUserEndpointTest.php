<?php

namespace Test\Integration\User;

use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;
use Test\Integration\EndpointTest;

class GetUserEndpointTest extends EndpointTest
{
    public function test_unauthorized_returns_401()
    {
        $response = $this->client->get('/user');
        $this->assertEquals(401, $response->getStatusCode());

    }

    public function test_status_200_when_credentials_succeed()
    {
        try{
            $this->registerUser("valid@email.com","mypass");
            $loginResponse = $this->login("valid@email.com", "mypass");
            $loginData = json_decode($loginResponse->getBody(), true);

            $response = $this->client->get('/user',[
                'headers' => [
                    'Authorization' => 'Bearer '.$loginData['token']
                ]
            ]);
            $this->assertEquals(200, $response->getStatusCode());
        } catch (RequestException $e) {
            $this->fail($e->getMessage());
        }
    }

    public function tearDown(): void
    {
        parent::tearDown();
        $this->deleteUser("valid@email.com");
    }

    private function login(string $email, string $password): ResponseInterface
    {
        return $this->client->post('/login', [
            'body' => json_encode([
                'email' => $email,
                'password' => $password
            ], JSON_THROW_ON_ERROR)
        ]);
    }
}
