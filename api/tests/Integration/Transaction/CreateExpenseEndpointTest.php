<?php

namespace Test\Integration\Transaction;

use Test\Integration\EndpointTest;

class CreateExpenseEndpointTest extends EndpointTest
{
    const EMAIL = "expense@email.com";

    public function test_expense_can_be_created()
    {
        $this->registerUser(self::EMAIL, "pass");
        $this->login(self::EMAIL, "pass");

        $response = $this->client->post('/expense',[
            'body' => json_encode([
                'amount' => 3000,
                'description' => "casino",
                'date' => "2023-04-25"
            ], JSON_THROW_ON_ERROR),
            'headers' => [
                'Authorization' => 'Bearer ' . $this->authToken
            ]
        ]);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function tearDown(): void
    {
        parent::tearDown();
        $this->deleteUser(self::EMAIL);
        $pdo = $this->container->get(\PDO::class);
        $pdo->query('DELETE FROM expense WHERE description="casino";');
    }
}
