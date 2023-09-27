<?php

namespace Test\Integration\Transaction;

use Test\Integration\EndpointTest;

class CreateExpenseEndpointTest extends EndpointTest
{
    public function test_expense_can_be_created()
    {
        $this->registerUser($this->email, "pass");
        $this->login($this->email, "pass");

        $response = $this->client->post('expense',[
            'body' => json_encode([
                'amount' => 3000,
                'description' => "test_expense_created",
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
        $this->deleteUser($this->email);
        $this->pdo->query('DELETE FROM expense WHERE description="test_expense_created";');
    }
}
