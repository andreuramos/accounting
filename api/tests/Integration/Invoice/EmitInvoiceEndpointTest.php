<?php

namespace Test\Integration\Invoice;

use Test\Integration\EndpointTest;

class EmitInvoiceEndpointTest extends EndpointTest
{
    private string $invoiceNumber = '';

    public function test_unauthorized_fails()
    {
        $response = $this->client->post('/invoice');

        $this->assertEquals(401, $response->getStatusCode());
    }

    public function test_no_business_authorized_user_fails()
    {
        $this->registerUser($this->email, "");
        $this->login($this->email, "");

        $response = $this->client->post('/invoice', [
            'body' => json_encode([
                'customer_name' => "CUSTOMER Bar",
                'customer_tax_name' => "Jaume de s'Atomic",
                'customer_tax_number' => "435678122F",
                'customer_tax_address' => "Cardenal despuig 41",
                'customer_tax_zip_code' => "07013",
                'date' => '2023-06-27',
                'lines' => [
                    [
                        'amount' => 1000,
                        'concept' => 'Caja de 12 Moixes',
                        'vat_percent' => 21,
                    ],
                ],
            ], JSON_THROW_ON_ERROR),
            'headers' => ['Authorization' => 'Bearer ' . $this->authToken],
        ]);

        $this->assertEquals(400, $response->getStatusCode());
    }

    public function test_no_lines_fails()
    {
        $this->registerUser($this->email, "");
        $this->login($this->email, "");
        $this->setBusinessData();

        $response = $this->client->post('/invoice', [
            'body' => json_encode([
                'customer_name' => "CUSTOMER Bar",
                'customer_tax_name' => "Jaume de s'Atomic",
                'customer_tax_number' => "435678122F",
                'customer_tax_address' => "Cardenal despuig 41",
                'customer_tax_zip_code' => "07013",
                'date' => '2023-06-27',
                'lines' => [],
            ], JSON_THROW_ON_ERROR),
            'headers' => ['Authorization' => 'Bearer ' . $this->authToken],
        ]);

        $this->assertEquals(400, $response->getStatusCode());
    }

    public function test_authroized_returns_invoice_number()
    {
        $this->registerUser($this->email, "");
        $this->login($this->email, "");
        $this->setBusinessData();

        $response = $this->client->post('/invoice', [
            'body' => json_encode([
                'customer_name' => "CUSTOMER Bar",
                'customer_tax_name' => "Jaume de s'Atomic",
                'customer_tax_number' => "435678122F",
                'customer_tax_address' => "Cardenal despuig 41",
                'customer_tax_zip_code' => "07013",
                'date' => '2023-06-27',
                'lines' => [
                    [
                        'amount' => 1000,
                        'concept' => 'Capsa de 12 Moixes',
                        'vat_percent' => 21,
                    ],
                ],
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
        $this->deleteUser($this->email);
        $this->pdo->query('DELETE FROM business WHERE tax_id = "435678122F";');
        $this->pdo->query('DELETE FROM business WHERE tax_id = "EMIT0012300TEST";');
        if ($this->invoiceNumber) {
            $this->pdo->query('DELETE FROM invoice_line WHERE invoice_id IN (SELECT id from invoice WHERE number="'. $this->invoiceNumber.'")');
            $this->pdo->query('DELETE FROM income WHERE id IN (SELECT income_id FROM invoice WHERE number="' . $this->invoiceNumber . '")');
            $this->pdo->query('DELETE FROM invoice WHERE number = "'.$this->invoiceNumber.'"');
        }
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
