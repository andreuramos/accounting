<?php

namespace Test\Integration\Invoice;

use PDO;
use Test\Integration\EndpointTest;

class EmitInvoiceEndpointTest extends EndpointTest
{
    private string $invoiceNumber = '';

    public function test_unauthorized_fails()
    {
        $response = $this->client->post('invoice');

        $this->assertEquals(401, $response->getStatusCode());
    }

    public function test_no_business_authorized_user_fails()
    {
        $this->registerUser($this->email, "");
        $this->login($this->email, "");

        $response = $this->client->post('invoice', [
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

        $this->markTestIncomplete("Needs to receive a 400 instead of a 500");
        $this->assertEquals(400, $response->getStatusCode());
    }

    public function test_no_lines_fails()
    {
        $this->registerUser($this->email, "");
        $this->login($this->email, "");
        $this->setBusinessData();

        $response = $this->client->post('invoice', [
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

        $this->markTestIncomplete("Needs to receive a 400 instead of a 500");
        $this->assertEquals(400, $response->getStatusCode());
    }

    public function test_authorized_returns_invoice_number()
    {
        $this->registerUser($this->email, "");
        $this->login($this->email, "");
        $this->setBusinessData();

        $response = $this->client->post('invoice', [
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
        $this->assertInvoiceExistsInDatabase($this->invoiceNumber);
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
        $this->client->post('user/tax_data', [
            'body' => json_encode([
                'tax_name' => "test emitinvoice",
                'tax_number' => "EMIT0012300TEST",
                'tax_address_street' => 'fake st 123',
                'tax_address_zip_code' => '07013'
            ], JSON_THROW_ON_ERROR),
            'headers' => ['Authorization' => 'Bearer ' . $this->authToken],
        ]);
    }

    private function assertInvoiceExistsInDatabase(string $invoiceNumber): void
    {
        $business_stmt = $this->pdo->prepare('SELECT business_id FROM user WHERE email = :email ORDER BY id DESC LIMIT 1;');
        $business_stmt->bindParam(':email', $this->email);
        $business_stmt->execute();
        $user = $business_stmt->fetch();
        
        $invoice_stmt = $this->pdo->prepare('SELECT id FROM invoice WHERE number = :invoice_number AND emitter_id = :business_id;');
        $invoice_stmt->bindParam(':invoice_number', $invoiceNumber);
        $invoice_stmt->bindParam(':business_id', $user['business_id']);
        $invoice_stmt->execute();
        $invoice_id = $invoice_stmt->fetch();
        $this->assertNotFalse($invoice_id);
        $this->assertArrayHasKey('id', $invoice_id);
    }
}
