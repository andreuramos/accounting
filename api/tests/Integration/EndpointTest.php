<?php

namespace Test\Integration;

use App\Shared\Infrastructure\ContainerFactory;
use DI\Container;
use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

abstract class EndpointTest extends TestCase
{
    protected Client $client;
    protected Container $container;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = new Client([
            'base_uri' => 'http://nginx',
            'http_errors' => false
        ]);
        $this->container = ContainerFactory::create();
    }

    protected function registerUser(string $email, string $password)
    {
        $this->client->post('/user',[
            'body' => json_encode([
                'email' => $email,
                'password' => $password
            ], JSON_THROW_ON_ERROR)
        ]);
    }

    protected function deleteUser(string $email): void
    {
        $pdo = $this->container->get(\PDO::class);
        $pdo->query('DELETE FROM user WHERE email="' . $email . '";');
    }
}
