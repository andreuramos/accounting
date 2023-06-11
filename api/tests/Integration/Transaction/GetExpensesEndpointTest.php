<?php

namespace Test\Integration\Transaction;

use Test\Integration\EndpointTest;

class GetExpensesEndpointTest extends EndpointTest
{
    public function test_returns_status_200()
    {
        $this->registerUser($this->email, "124");
        $this->login($this->email, "124");

        $response = $this->client->get('/expense', [
            'headers' => [
                'Authorization' => "Bearer " . $this->authToken,
            ]
        ]);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_returns_created_expenses()
    {
        $this->registerUser($this->email, "124");
        $this->login($this->email, "124");

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

    public function test_returns_logged_user_only_expenses()
    {
        $this->registerUser('other@GetExpensesEndpointTest.com', '1');
        $this->login('other@GetExpensesEndpointTest.com', '1');
        $this->client->post('/expense', [
            'body' => json_encode([
                'amount' => 10,
                'description' => 'should not be listed',
                'date' => '2023-06-10',
            ], JSON_THROW_ON_ERROR),
            'headers' => ['Authorization' => 'Bearer ' . $this->authToken]
        ]);

        $this->registerUser($this->email, "124");
        $this->login($this->email, "124");

        $this->client->post('/expense', [
            'body' => json_encode([
                'amount' => 100,
                'description' => "expense from logged user",
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
        $this->assertEquals('expense from logged user', $returnedExpense['description']);
        $this->assertEquals('2023-05-01', $returnedExpense['date']);
    }

    public function tearDown(): void
    {
        parent::tearDown();
        $this->deleteUser($this->email);
        $this->pdo->query('DELETE FROM expense WHERE description = "test_returns_created_expense"');
        $this->pdo->query('DELETE FROM expense WHERE description = "expense from logged user"');
        $this->pdo->query('DELETE FROM expense WHERE description = "should not be listed"');
    }
}
