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
    
    public function test_authorized_returns_200(): void
    {
        $this->registerUser($this->email, "");
        $this->login($this->email, "");
        
        $response = $this->client->get('invoice');
        
        $this->assertEquals(200, $response->getStatusCode());
    }
}