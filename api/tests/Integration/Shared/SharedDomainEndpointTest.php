<?php

namespace Test\Integration\Shared;

use GuzzleHttp\Exception\RequestException;
use Test\Integration\EndpointTest;

class SharedDomainEndpointTest extends EndpointTest
{
    public function testSharedDomainReturns200()
    {
        try {
            $response = $this->client->get('/domain');
            $this->assertEquals(200, $response->getStatusCode());
        } catch (RequestException $e) {
            $this->fail($e->getMessage());
        }
    }
}
