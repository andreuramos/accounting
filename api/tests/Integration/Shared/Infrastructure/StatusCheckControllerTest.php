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
            $response = $client->get('accounting_nginx_1:8080/status');
            $this->assertEquals(200, $response->getStatusCode());
        } catch (RequestException $e) {
            $this->fail($e->getMessage());
        }
    }
}
