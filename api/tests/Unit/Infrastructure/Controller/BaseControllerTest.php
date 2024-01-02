<?php

namespace Test\Unit\Infrastructure\Controller;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

abstract class BaseControllerTest extends TestCase
{
    public function buildRequest(array $body): Request
    {
        return new Request(
            [], [], [], [], [], [], json_encode($body, JSON_THROW_ON_ERROR)
        );
    }
}