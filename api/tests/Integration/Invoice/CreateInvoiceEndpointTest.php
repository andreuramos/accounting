<?php

namespace Test\Integration\Invoice;

use Test\Integration\EndpointTest;

class CreateInvoiceEndpointTest extends EndpointTest
{
    public function test_unauthorized_fails()
    {
        $response = $this->client->post('/invoice');

        $this->assertEquals(401, $response->getStatusCode());
    }
}
