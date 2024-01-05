<?php

namespace Test\Unit\Infrastructure\Controller;

use App\Domain\Exception\InvalidCredentialsException;
use App\Domain\Exception\MissingMandatoryParameterException;
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
    
    public function test_fails_if_missing_parameter(): void
    {
        $request = $this->buildAuthorizedRequest([
            'provider_name' => "BREWERY",
            'provider_tax_name' => "Brewery SL",
            //'provider_tax_number' => "B076546546",
            'provider_tax_address' => 'Camp Llarg 20',
            'provider_tax_zip_code' => '07130',
            'invoice_number' => '20230000232',
            'description' => "Lot 3 cervesa moixa",
            'date' => '2024-01-03',
            'amount' => 3000_00,
            'taxes' => 600_00,
        ]);
        $controller = $this->getController();

        $this->expectException(MissingMandatoryParameterException::class);

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