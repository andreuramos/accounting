<?php

namespace Test\Unit\User\Infrastructure\Controller;

use App\User\Infrastructure\Controller\RegisterUserController;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RegisterUserControllerTest extends TestCase
{
    public function test_it_returns_a_response()
    {
        $controller = new RegisterUserController();
        $request = new Request();

        $response = $controller($request);

        $this->assertInstanceOf(Response::class, $response);
    }
}
