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
    protected string $authToken;
    protected \PDO $pdo;
    protected string $email = "";

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = new Client([
            'base_uri' => 'http://nginx',
            'http_errors' => false
        ]);
        $this->container = ContainerFactory::create();
        $this->authToken = "";
        $this->pdo = $this->container->get(\PDO::class);

        $class = explode('\\', static::class);
        $this->email = $this->getName() . '@' . end($class) . '.test';

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

    protected function login(string $email, string $password)
    {
        $response = $this->client->post('/login', [
            'body' => json_encode([
                'email' => $email,
                'password' => $password
            ], JSON_THROW_ON_ERROR)
        ]);

        if ($response->getStatusCode() === 200) {
            $loginData = json_decode($response->getBody()->getContents(), true);
            $this->authToken = $loginData['token'];
        }
    }

    protected function deleteUser(string $email): void
    {
        $userQuery = $this->pdo->query('SELECT * FROM user WHERE email="' . $email .'";');
        $userQuery->execute();
        $user = $userQuery->fetch();

        if (false !== $user) {
            $this->pdo->query('DELETE FROM account WHERE main_user_id = ' . $user['id']);
            $this->pdo->query('DELETE FROM user WHERE id="' . $user['id'] . '";');
        }
    }
}
