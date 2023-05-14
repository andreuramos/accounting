<?php

namespace Test\Unit\Invoice\Infrastructure\Controller;

use App\Invoice\Infrastructure\Controller\CreateInvoiceController;
use App\Shared\Domain\Exception\MissingMandatoryParameterException;
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

    public function test_fails_if_missing_parameters()
    {
        $request = $this->buildAuthorizedRequest([
            "customer_name" => "Atomic Garden",
            "customer_tax_name" => "Atomic Garden SL",
            "customer_tax_number" => "43568953F",
            "customer_tax_address" => "Carrer fals 123",
            "customer_tax_zip_code" => "07014"
        ]);
        $controller = $this->getController();

        $this->expectException(MissingMandatoryParameterException::class);

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
