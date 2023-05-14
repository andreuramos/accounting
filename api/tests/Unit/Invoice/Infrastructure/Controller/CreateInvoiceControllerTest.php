<?php

namespace Test\Unit\Invoice\Infrastructure\Controller;

use App\Invoice\Application\Command\CreateInvoiceCommand;
use App\Invoice\Application\UseCase\CreateInvoiceUseCase;
use App\Invoice\Domain\ValueObject\InvoiceNumber;
use App\Invoice\Infrastructure\Controller\CreateInvoiceController;
use App\Shared\Domain\Exception\MissingMandatoryParameterException;
use App\User\Domain\Exception\InvalidCredentialsException;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Symfony\Component\HttpFoundation\Request;
use Test\Unit\Shared\Infrastructure\Controller\AuthorizedControllerTest;

class CreateInvoiceControllerTest extends AuthorizedControllerTest
{
    use ProphecyTrait;

    private $useCase;

    public function setUp(): void
    {
        parent::setUp();
        $this->useCase = $this->prophesize(CreateInvoiceUseCase::class);
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

    public function test_calls_usecase_and_returns_result()
    {
        $request = $this->buildAuthorizedRequest([
            "income_id" => 123,
            "customer_name" => "Atomic Garden",
            "customer_tax_name" => "Atomic Garden SL",
            "customer_tax_number" => "43568953F",
            "customer_tax_address" => "Carrer fals 123",
            "customer_tax_zip_code" => "07014",
        ]);
        $invoiceNumber = new InvoiceNumber('2023000001');
        $this->useCase->__invoke(Argument::type(CreateInvoiceCommand::class))
            ->shouldBeCalled()
            ->willReturn($invoiceNumber);
        $controller = $this->getController();

        $response = $controller($request);

        $decodedResponse = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('invoice_number', $decodedResponse);
        $this->assertEquals('2023000001', $decodedResponse['invoice_number']);

    }

    private function getController(): CreateInvoiceController
    {
        return new CreateInvoiceController(
            $this->tokenDecoder->reveal(),
            $this->userRepository->reveal(),
            $this->useCase->reveal(),
        );
    }
}
