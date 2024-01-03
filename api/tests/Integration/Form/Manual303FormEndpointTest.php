<?php

namespace Integration\Form;

use Test\Integration\EndpointTest;

class Manual303FormEndpointTest extends EndpointTest
{
    public function test_correct_request_returns_importable_file(): void
    {
        $response = $this->client->post('form/303', [
            'body' => json_encode([
                "tax_name" => "ROSSO ACEITUNO JULIAN",
                "tax_id" => "59519037M",
                "year" => 2022,
                "quarter" => 2,
                "accrued_base" => 741_45,
                "accrued_tax" => 155_71,
                "deductible_base" => 4527_29,
                "deductible_tax" => 950_73,
                "iban" => "ES9701280581210100059701",
                "pending_from_other_periods" => 0,
            ])
        ]);
        self::assertEquals(200, $response->getStatusCode());
        $headers = $response->getHeaders();
        self::assertStringContainsString('text/plain', $headers['Content-Type'][0]);
    }
}