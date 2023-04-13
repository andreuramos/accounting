<?php

namespace Test\Unit\User\Infrastructure\Controller;

use App\User\Infrastructure\Controller\LoginController;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\MissingMandatoryParametersException;

class LoginControllerTest extends TestCase
{
    use ProphecyTrait;

    public function test_throws_exception_if_missing_parameters(): void
    {
        $request = $this->prophesize(Request::class);
        $request->getContent()->willReturn(json_encode([]));
        $controller = new LoginController();

        $this->expectException(MissingMandatoryParametersException::class);

        $controller($request->reveal());
    }
}
