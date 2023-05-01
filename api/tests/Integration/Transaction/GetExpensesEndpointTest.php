<?php

namespace Test\Integration\Transaction;

use Test\Integration\EndpointTest;

class GetExpensesEndpointTest extends EndpointTest
{
    const TEST_EMAIL = "get@expense.test";

    public function test_returns_status_200()
    {
        $this->registerUser(self::TEST_EMAIL, "124");
        $this->login(self::TEST_EMAIL, "124");

        $response = $this->client->get('/expense', [
            'headers' => [
                'Authorization' => "Bearer " . $this->authToken,
            ]
        ]);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_returns_created_expenses()
    {
        $this->registerUser(self::TEST_EMAIL, "124");
        $this->login(self::TEST_EMAIL, "124");

        $this->client->post('/expense', [
            'body' => json_encode([
                'amount' => 100,
                'description' => "test_returns_created_expense",
                'date' => '2023-05-01'
            ], JSON_THROW_ON_ERROR),
            'headers' => ['Authorization' => 'Bearer ' . $this->authToken]
        ]);

        $response = $this->client->get('/expense', [
            'headers' => ['Authorization' => "Bearer " . $this->authToken,]
        ]);

        $returnedExpenses = json_decode($response->getBody(), true);
        $this->assertCount(1, $returnedExpenses);
        $returnedExpense = $returnedExpenses[0];
        $this->assertEquals(100, $returnedExpense['amount_cents']);
        $this->assertEquals('test_returns_created_expense', $returnedExpense['description']);
        $this->assertEquals('2023-05-01', $returnedExpense['date']);
    }

    public function tearDown(): void
    {
        parent::tearDown();
        $this->deleteUser(self::TEST_EMAIL);
        $pdo = $this->container->get(\PDO::class);
        $pdo->query('DELETE FROM expense WHERE description = "test_returns_created_expense"');
    }
}
