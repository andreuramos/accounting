<?php

namespace Test\Integration\Tax;

use Test\Integration\EndpointTest;

class SetTaxDataEndpointTest extends EndpointTest
{
    public function test_unauthorized_returns_401()
    {
        $response = $this->client->post('/tax_data');
        $this->assertEquals(401, $response->getStatusCode());
    }
}
