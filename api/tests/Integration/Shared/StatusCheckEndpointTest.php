<?php

namespace Test\Integration\Shared;

use GuzzleHttp\Exception\RequestException;
use Test\Integration\EndpointTest;

class StatusCheckEndpointTest extends EndpointTest
{
    public function testStatusCheckReturns200()
    {
        try {
            $response = $this->client->get('status');
            $this->assertEquals(200, $response->getStatusCode());
        } catch (RequestException $e) {
            $this->fail($e->getMessage());
        }
    }
}
