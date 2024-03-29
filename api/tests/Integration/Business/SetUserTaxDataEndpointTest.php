<?php

namespace Test\Integration\Business;

use Test\Integration\EndpointTest;

class SetUserTaxDataEndpointTest extends EndpointTest
{
    public function test_unauthorized_returns_401()
    {
        $response = $this->client->post('user/tax_data');
        $this->assertEquals(401, $response->getStatusCode());
    }

    public function test_correct_request_returns_200()
    {
        $this->registerUser($this->email, "");
        $this->login($this->email, "");

        $response = $this->client->post('user/tax_data', [
            'body' => json_encode([
                'tax_name' => "SET TAX DATA TEST",
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
        $this->deleteUser($this->email);
        $this->pdo->query('DELETE FROM business WHERE name = "SET TAX DATA TEST"');
    }
}
