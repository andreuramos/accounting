<?php

namespace Test\Integration\Shared\Infrastructure;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use PHPUnit\Framework\TestCase;

class StatusCheckControllerTest extends TestCase
{
    public function testStatusCheckReturns200()
    {
        $client = new Client();
        try {
            $response = $client->get('http://accounting_nginx_1/status');
            $this->assertEquals(200, $response->getStatusCode());
        } catch (RequestException $e) {
            $this->fail($e->getMessage());
        }
    }
}
