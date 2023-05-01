<?php

namespace Test\Integration\Transaction;

use Test\Integration\EndpointTest;

class GetExpensesEndpointTest extends EndpointTest
{
    const TEST_EMAIL = "get@expense.test";

    public function test_returns_expected_expenses()
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

    public function tearDown(): void
    {
        parent::tearDown();
        $this->deleteUser(self::TEST_EMAIL);
    }
}
