<?php

namespace Test\Integration\Shared\Infrastructure;

use GuzzleHttp\Exception\RequestException;
use Test\Integration\EndpointTest;

class StatusCheckControllerTest extends EndpointTest
{
    public function testStatusCheckReturns200()
    {
        try {
            $response = $this->client->get('/status');
            $this->assertEquals(200, $response->getStatusCode());
        } catch (RequestException $e) {
            $this->fail($e->getMessage());
        }
    }
}
