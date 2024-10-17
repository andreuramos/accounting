<?php

namespace Integration\Invoice;

use Test\Integration\EndpointTest;

class GetInvoicesEndpointTest extends EndpointTest
{
    public function test_unauthorized_fails(): void
    {
        $response = $this->client->get('invoice');
        
        $this->assertEquals(401, $response->getStatusCode());
    }
}