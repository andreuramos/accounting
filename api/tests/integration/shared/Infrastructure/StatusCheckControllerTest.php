<?php

namespace test\integration\Shared\Infrastructure;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use PHPUnit\Framework\TestCase;

class StatusCheckControllerTest extends TestCase
{
    public function testStatusCheckReturns200()
    {
        $client = new Client();
        try {
            $response = $client->get('http://nginx/status');
            $this->assertEquals(200, $response->getStatusCode());
        } catch (RequestException $e) {
            $this->fail($e->getMessage());
        }
    }
}
