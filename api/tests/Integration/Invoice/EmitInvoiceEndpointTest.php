<?php

namespace Test\Integration\Invoice;

use Test\Integration\EndpointTest;

class EmitInvoiceEndpointTest extends EndpointTest
{
    private const EMAIL = "create@invoice.test";
    private $incomeId;

    public function test_unauthorized_fails()
    {
        $response = $this->client->post('/invoice');

        $this->assertEquals(401, $response->getStatusCode());
    }

    public function test_authroized_returns_invoice_number()
    {
        $this->registerUser(self::EMAIL, "");
        $this->login(self::EMAIL, "");
        $incomeResponse = $this->client->post('/income', [
            'body' => json_encode([
                'amount' => 1000,
                'description' => "stuff",
                'date' => '2023-05-14',
            ], JSON_THROW_ON_ERROR),
            'headers' => ['Authorization' => 'Bearer ' . $this->authToken],
        ]);
        $income = json_decode($incomeResponse->getBody(), true);
        $this->incomeId = (int) $income['id'];

        $response = $this->client->post('/invoice', [
            'body' => json_encode([
                'income_id' => $income['id'],
                'customer_name' => "Atomic Garden",
                'customer_tax_name' => "Jaume de s'Atomic",
                'customer_tax_number' => "435678122F",
                'customer_tax_address' => "Cardenal despuig 41",
                'customer_tax_zip_code' => "07013",
            ], JSON_THROW_ON_ERROR),
            'headers' => ['Authorization' => 'Bearer ' . $this->authToken],
        ]);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('invoice_number', json_decode($response->getBody(), true));
    }

    public function tearDown(): void
    {
        parent::tearDown();
        $this->deleteUser(self::EMAIL);
        if ($this->incomeId) {
            $this->pdo->query('DELETE FROM income WHERE id=' . $this->incomeId . ';');
        }
    }
}
