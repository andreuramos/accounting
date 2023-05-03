<?php

namespace Test\Unit\Transaction\Infrastructure\Controller;

use App\Transaction\Infrastructure\Controller\CreateIncomeController;
use App\User\Domain\Exception\InvalidCredentialsException;
use Symfony\Component\HttpFoundation\Request;
use Test\Unit\Shared\Infrastructure\Controller\ControllerTest;

class CreateIncomeControllerTest extends ControllerTest
{
    public function test_unauthorized_fails()
    {
        $request = new Request();
        $controller = $this->buildController();

        $this->expectException(InvalidCredentialsException::class);

        $controller($request);
    }

    private function buildController(): CreateIncomeController
    {
        return new CreateIncomeController(
            $this->tokenDecoder->reveal(),
            $this->userRepository->reveal(),
        );
    }
}
