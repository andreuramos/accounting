<?php

namespace Test\Integration\Shared\Infrastructure;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use PHPUnit\Framework\TestCase;

class SharedDomainControllerTest extends TestCase
{
    public function testSharedDomainReturns200()
    {
        $client = new Client();
        try {
            $response = $client->get('http://nginx:8080/domain');
            $this->assertEquals(200, $response->getStatusCode());
        } catch (RequestException $e) {
            $this->fail($e->getMessage());
        }
    }
}
