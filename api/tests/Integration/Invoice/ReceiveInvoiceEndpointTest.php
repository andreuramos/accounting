<?php

namespace Integration\Invoice;

use Test\Integration\EndpointTest;

class ReceiveInvoiceEndpointTest extends EndpointTest
{
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
        
        $response = $this->client->post('invoice/receive', [
            'body' => json_encode([
                'provider_name' => "SULLERICA",
                'provider_tax_name' => "Sullerica SL",
                'provider_tax_number' => "B076546546",
                'provider_tax_address' => 'Camp Llarg 20',
                'provider_tax_zip_code' => '07130',
                'invoice_number' => '20230000232',
                'description' => "Lot 3 cervesa moixa",
                'date' => '2024-01-03',
                'amount' => 3000_00,
                'taxes' => 600_00,
            ]),
            'headers' => ['Authorization' => 'Bearer ' . $this->authToken],
        ]);
        
        self::assertEquals(201, $response->getStatusCode());
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
}