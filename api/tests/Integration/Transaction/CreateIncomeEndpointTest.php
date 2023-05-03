<?php

namespace Test\Integration\Transaction;

use Test\Integration\EndpointTest;

class CreateIncomeEndpointTest extends EndpointTest
{
    const TEST_EMAIL = "create@income.app";

    public function test_returns_status_200()
    {
        $this->registerUser(self::TEST_EMAIL, "");
        $this->login(self::TEST_EMAIL, "");
        $response = $this->client->post('/income',[
            'body' => json_encode([
                'amount' => 100,
                'description' => 'success income',
                'date' => '2023-05-03',
            ], JSON_THROW_ON_ERROR),
            'headers' => ['Authorization' => 'Bearer '.$this->authToken]
        ]);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function tearDown(): void
    {
        parent::tearDown();
        $this->deleteUser(self::TEST_EMAIL);
    }
}
