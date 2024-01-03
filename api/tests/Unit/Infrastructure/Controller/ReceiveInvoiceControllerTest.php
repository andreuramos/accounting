<?php

namespace Test\Unit\Infrastructure\Controller;

use App\Domain\Exception\InvalidCredentialsException;
use App\Infrastructure\Controller\ReceiveInvoiceController;
use Symfony\Component\HttpFoundation\Request;

class ReceiveInvoiceControllerTest extends AuthorizedControllerTest
{
    public function test_fails_if_unauthorized(): void
    {
        $request = new Request();
        $controller = $this->getController();

        $this->expectException(InvalidCredentialsException::class);

        $controller($request);
    }

    private function getController(): ReceiveInvoiceController
    {
        return new ReceiveInvoiceController(
            $this->tokenDecoder->reveal(),
            $this->userRepository->reveal(),
        );
    }
}