<?php

namespace Test\Integration;

use App\Shared\Infrastructure\ContainerFactory;
use DI\Container;
use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

class EndpointTest extends TestCase
{
    protected Client $client;
    protected Container $container;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = new Client(['base_uri' => 'http://nginx']);
        $this->container = ContainerFactory::create();
    }
}
