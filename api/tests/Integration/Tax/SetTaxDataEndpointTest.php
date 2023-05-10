<?php

namespace Test\Integration\Tax;

use Test\Integration\EndpointTest;

class SetTaxDataEndpointTest extends EndpointTest
{
    const EMAIL = "set@taxdata.test";

    public function test_unauthorized_returns_401()
    {
        $response = $this->client->post('/tax/data');
        $this->assertEquals(401, $response->getStatusCode());
    }

    public function test_correct_request_returns_200()
    {
        $this->registerUser(self::EMAIL, "");
        $this->login(self::EMAIL, "");

        $response = $this->client->post('/tax/data', [
            'body' => json_encode([
                'tax_name' => "Moixa Brewing SL",
                'tax_number' => "B07656565",
                'tax_address_street' => "Andreu Jaume Nadal 29",
                'tax_address_zip_code' => "07013",
            ], JSON_THROW_ON_ERROR),
            'headers' => ['Authorization' => 'Bearer '.$this->authToken ]
        ]);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function tearDown(): void
    {
        parent::tearDown();
        $this->deleteUser(self::EMAIL);
    }
}
