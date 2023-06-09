<?php

namespace Test\Integration\Transaction;

use Test\Integration\EndpointTest;

class GetIncomesEndpointTest extends EndpointTest
{
    public function test_returns_stored_incomes()
    {
        $this->registerUser($this->email, "123");
        $this->login($this->email, "123");

        $this->client->post('/income', [
            'body' => json_encode([
                'amount' => 100,
                'description' => "test_returns_created_income",
                'date' => '2023-05-01'
            ], JSON_THROW_ON_ERROR),
            'headers' => ['Authorization' => 'Bearer ' . $this->authToken]
        ]);

        $response = $this->client->get('/income', [
            'headers' => ['Authorization' => "Bearer " . $this->authToken,]
        ]);

        $returnedIncomes = json_decode($response->getBody(), true);
        $this->assertCount(1, $returnedIncomes);
        $returnedIncome = $returnedIncomes[0];
        $this->assertEquals(100, $returnedIncome['amount_cents']);
        $this->assertEquals('test_returns_created_income', $returnedIncome['description']);
        $this->assertEquals('2023-05-01', $returnedIncome['date']);
    }

    public function tearDown(): void
    {
        parent::tearDown();
        $this->deleteUser($this->email);
        $this->pdo->query('DELETE FROM income WHERE description = "test_returns_created_income"');

    }
}
