<?php

namespace Test\Integration\Transaction;

use Test\Integration\EndpointTest;

class CreateIncomeEndpointTest extends EndpointTest
{
    public function test_returns_status_200()
    {
        $this->registerUser($this->email, "");
        $this->login($this->email, "");
        $response = $this->client->post('income',[
            'body' => json_encode([
                'amount' => 100,
                'description' => 'test_income_created',
                'date' => '2023-05-03',
            ], JSON_THROW_ON_ERROR),
            'headers' => ['Authorization' => 'Bearer '.$this->authToken]
        ]);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_returns_created_id()
    {
        $this->registerUser($this->email, "");
        $this->login($this->email, "");
        $response = $this->client->post('income',[
            'body' => json_encode([
                'amount' => 100,
                'description' => 'test_income_created',
                'date' => '2023-05-03',
            ], JSON_THROW_ON_ERROR),
            'headers' => ['Authorization' => 'Bearer '.$this->authToken]
        ]);

        $decodedResponse = json_decode($response->getBody(), true);
        $this->assertArrayHasKey('id', $decodedResponse);
    }

    public function tearDown(): void
    {
        parent::tearDown();
        $this->deleteUser($this->email);
        $this->pdo->query('DELETE FROM income WHERE description="test_income_created"');
    }
}
