<?php

namespace Test\Unit\Invoice\Infrastructure\Controller;

use App\Invoice\Infrastructure\Controller\CreateInvoiceController;
use App\User\Domain\Exception\InvalidCredentialsException;
use Symfony\Component\HttpFoundation\Request;
use Test\Unit\Shared\Infrastructure\Controller\AuthorizedControllerTest;

class CreateInvoiceControllerTest extends AuthorizedControllerTest
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_fails_if_unauthorized()
    {
        $request = new Request();
        $controller = $this->getController();

        $this->expectException(InvalidCredentialsException::class);

        $controller($request);
    }

    private function getController(): CreateInvoiceController
    {
        return new CreateInvoiceController(
            $this->tokenDecoder->reveal(),
            $this->userRepository->reveal(),
        );
    }
}
