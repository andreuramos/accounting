<?php

namespace Test\Unit\Invoice\Infrastructure\Controller;

use App\Invoice\Application\Command\EmitInvoiceCommand;
use App\Invoice\Application\UseCase\EmitInvoiceUseCase;
use App\Invoice\Domain\ValueObject\InvoiceNumber;
use App\Invoice\Infrastructure\Controller\EmitInvoiceController;
use App\Shared\Domain\Exception\MissingMandatoryParameterException;
use App\User\Domain\Exception\InvalidCredentialsException;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Symfony\Component\HttpFoundation\Request;
use Test\Unit\Shared\Infrastructure\Controller\AuthorizedControllerTest;

class EmitInvoiceControllerTest extends AuthorizedControllerTest
{
    use ProphecyTrait;

    private $useCase;

    public function setUp(): void
    {
        parent::setUp();
        $this->useCase = $this->prophesize(EmitInvoiceUseCase::class);
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

    public function test_fails_if_lines_is_empty()
    {
        $request = $this->buildAuthorizedRequest([
            "customer_name" => "Atomic Garden",
            "customer_tax_name" => "Atomic Garden SL",
            "customer_tax_number" => "43568953F",
            "customer_tax_address" => "Carrer fals 123",
            "customer_tax_zip_code" => "07014",
            "lines" => [],
        ]);
        $controller = $this->getController();

        $this->expectException(MissingMandatoryParameterException::class);

        $controller($request);
    }

    public function test_invalid_invoice_line_fails()
    {
        $request = $this->buildAuthorizedRequest([
            "customer_name" => "Atomic Garden",
            "customer_tax_name" => "Atomic Garden SL",
            "customer_tax_number" => "43568953F",
            "customer_tax_address" => "Carrer fals 123",
            "customer_tax_zip_code" => "07014",
            "date" => "2023-06-27",
            "lines" => [
                [
                    "amount" => 1000,
                    "concept" => "Capsa de 12 Moixes",
                ],
            ],
        ]);
        $controller = $this->getController();

        $this->expectException(MissingMandatoryParameterException::class);

        $controller($request);
    }

    public function test_calls_usecase_and_returns_result()
    {
        $request = $this->buildAuthorizedRequest([
            "customer_name" => "Atomic Garden",
            "customer_tax_name" => "Atomic Garden SL",
            "customer_tax_number" => "43568953F",
            "customer_tax_address" => "Carrer fals 123",
            "customer_tax_zip_code" => "07014",
            "date" => "2023-06-27",
            "lines" => [
                [
                    "amount" => 1000,
                    "concept" => "Capsa de 12 Moixes",
                    "vat_percent" => 21,
                ],
            ],
        ]);
        $invoiceNumber = new InvoiceNumber('2023000001');
        $this->useCase->__invoke(Argument::type(EmitInvoiceCommand::class))
            ->shouldBeCalled()
            ->willReturn($invoiceNumber);
        $controller = $this->getController();

        $response = $controller($request);

        $decodedResponse = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('invoice_number', $decodedResponse);
        $this->assertEquals('2023000001', $decodedResponse['invoice_number']);

    }

    private function getController(): EmitInvoiceController
    {
        return new EmitInvoiceController(
            $this->tokenDecoder->reveal(),
            $this->userRepository->reveal(),
            $this->useCase->reveal(),
        );
    }
}
