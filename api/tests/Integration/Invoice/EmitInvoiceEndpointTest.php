<?php

namespace Test\Integration\Invoice;

use Test\Integration\EndpointTest;

class EmitInvoiceEndpointTest extends EndpointTest
{
    private const EMAIL = "create@invoice.test";
    private $incomeId;
    private $invoiceNumber;

    public function test_unauthorized_fails()
    {
        $response = $this->client->post('/invoice');

        $this->assertEquals(401, $response->getStatusCode());
    }

    public function test_no_business_authorized_user_fails()
    {
        $this->registerUser(self::EMAIL, "");
        $this->login(self::EMAIL, "");
        $income = $this->createIncome();
        $this->incomeId = (int) $income['id'];

        $response = $this->client->post('/invoice', [
            'body' => json_encode([
                'income_id' => $income['id'],
                'customer_name' => "CUSTOMER Bar",
                'customer_tax_name' => "Jaume de s'Atomic",
                'customer_tax_number' => "435678122F",
                'customer_tax_address' => "Cardenal despuig 41",
                'customer_tax_zip_code' => "07013",
            ], JSON_THROW_ON_ERROR),
            'headers' => ['Authorization' => 'Bearer ' . $this->authToken],
        ]);

        $this->assertEquals(400, $response->getStatusCode());
    }

    public function test_authroized_returns_invoice_number()
    {
        $this->registerUser(self::EMAIL, "");
        $this->login(self::EMAIL, "");
        $this->setBusinessData();
        $income = $this->createIncome();
        $this->incomeId = (int) $income['id'];

        $response = $this->client->post('/invoice', [
            'body' => json_encode([
                'income_id' => $income['id'],
                'customer_name' => "CUSTOMER Bar",
                'customer_tax_name' => "Jaume de s'Atomic",
                'customer_tax_number' => "435678122F",
                'customer_tax_address' => "Cardenal despuig 41",
                'customer_tax_zip_code' => "07013",
            ], JSON_THROW_ON_ERROR),
            'headers' => ['Authorization' => 'Bearer ' . $this->authToken],
        ]);

        $this->assertEquals(200, $response->getStatusCode());
        $decodedResponse = json_decode($response->getBody(), true);
        $this->invoiceNumber = $decodedResponse['invoice_number'];
        $this->assertArrayHasKey('invoice_number', $decodedResponse);
        $invoice = $this->pdo->query('SELECT * FROM invoice WHERE number = "'.$decodedResponse['invoice_number'].'"');
        $this->assertNotFalse($invoice);
    }

    public function tearDown(): void
    {
        parent::tearDown();
        $this->deleteUser(self::EMAIL);
        if ($this->incomeId) {
            $this->pdo->query('DELETE FROM income WHERE id=' . $this->incomeId . ';');
        }
        $this->pdo->query('DELETE FROM tax_data WHERE tax_number = "435678122F";');
        $this->pdo->query('DELETE FROM tax_data WHERE tax_number = "EMIT0012300TEST";');
        $this->pdo->query('DELETE FROM business WHERE name = "CUSTOMER Bar"');
        $this->pdo->query('DELETE FROM business WHERE name = "test emitinvoice"');
        $this->pdo->query('DELETE FROM invoice WHERE number = "'.$this->invoiceNumber.'"');
    }

       private function createIncome(): array
    {
        $incomeResponse = $this->client->post('/income', [
            'body' => json_encode([
                'amount' => 1000,
                'description' => "stuff",
                'date' => '2023-05-14',
            ], JSON_THROW_ON_ERROR),
            'headers' => ['Authorization' => 'Bearer ' . $this->authToken],
        ]);
        return json_decode($incomeResponse->getBody(), true);
    }

    private function setBusinessData(): void
    {
        $this->client->post('/user/tax_data', [
            'body' => json_encode([
                'tax_name' => "test emitinvoice",
                'tax_number' => "EMIT0012300TEST",
                'tax_address_street' => 'fake st 123',
                'tax_address_zip_code' => '07013'
            ], JSON_THROW_ON_ERROR),
            'headers' => ['Authorization' => 'Bearer ' . $this->authToken],
        ]);
    }
}
