<?php

namespace Integration\Invoice;

use Test\Integration\EndpointTest;

class ReceiveInvoiceEndpointTest extends EndpointTest
{
    const BASE_REQUEST = [
        'provider_name' => "SULLERICA",
        'provider_tax_name' => "Sullerica SL",
        'provider_tax_number' => "B076546546",
        'provider_tax_address' => 'Camp Llarg 20',
        'provider_tax_zip_code' => '07130',
        'description' => "Lot 3 cervesa moixa",
        'date' => '2024-01-03',
        'amount' => 3000_00,
        'taxes' => 600_00,
    ];

    public function test_unauthorized_fails(): void
    {
        $response = $this->client->post('invoice/receive');
        
        $this->assertEquals(401, $response->getStatusCode());
    }
    
    public function test_correct_request_returns_201(): void
    {
        $this->registerUser($this->email, "");
        $this->login($this->email, "");
        $this->setBusinessData();
        
        $request = array_merge(self::BASE_REQUEST, [
            'invoice_number' => '20230000232',
        ]);
        $response = $this->client->post('invoice/receive', [
            'body' => json_encode($request),
            'headers' => ['Authorization' => 'Bearer ' . $this->authToken],
        ]);
        
        self::assertEquals(201, $response->getStatusCode());
        $this->deleteInvoice('20230000232');
    }
    
    public function test_repeated_invoice_returns_400(): void
    {
        $this->registerUser($this->email, "");
        $this->login($this->email, "");
        $this->setBusinessData();

        $first_request = array_merge(self::BASE_REQUEST, [
            'invoice_number' => '20230000233',
        ]);
        $this->client->post('invoice/receive', [
            'body' => json_encode($first_request),
            'headers' => ['Authorization' => 'Bearer ' . $this->authToken],
        ]);
        $second_request = array_merge(self::BASE_REQUEST, [
            'invoice_number' => '20230000233',
        ]);
        $second_response = $this->client->post('invoice/receive', [
            'body' => json_encode($second_request),
            'headers' => ['Authorization' => 'Bearer ' . $this->authToken],
        ]);
        
        self::assertEquals(400, $second_response->getStatusCode());
        $this->deleteInvoice('20230000233');
    }

    private function setBusinessData(): void
    {
        $this->client->post('user/tax_data', [
            'body' => json_encode([
                'tax_name' => "test receiveinvoice",
                'tax_number' => "RECEIV0012300TEST",
                'tax_address_street' => 'fake st 123',
                'tax_address_zip_code' => '07013'
            ], JSON_THROW_ON_ERROR),
            'headers' => ['Authorization' => 'Bearer ' . $this->authToken],
        ]);
    }

    private function deleteInvoice(string $invoice_number)
    {
        $stmt = $this->pdo->prepare('DELETE FROM invoice WHERE number = :number');
        $stmt->bindParam(':number', $invoice_number);
        $stmt->execute();
    }
}