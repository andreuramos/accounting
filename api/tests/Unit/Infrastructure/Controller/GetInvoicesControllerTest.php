<?php

namespace Test\Unit\Infrastructure\Controller;

use App\Application\DTO\ExposableInvoices;
use App\Application\UseCase\GetInvoices\GetInvoicesCommand;
use App\Application\UseCase\GetInvoices\GetInvoicesUseCase;
use App\Domain\Entities\Business;
use App\Domain\Entities\Invoice;
use App\Domain\Entities\InvoiceAggregate;
use App\Domain\Exception\InvalidCredentialsException;
use App\Domain\ValueObject\Address;
use App\Domain\ValueObject\Id;
use App\Domain\ValueObject\InvoiceLine;
use App\Domain\ValueObject\InvoiceNumber;
use App\Domain\ValueObject\Money;
use App\Domain\ValueObject\Percentage;
use App\Infrastructure\Controller\GetInvoicesController;
use DateTime;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Symfony\Component\HttpFoundation\Request;

class GetInvoicesControllerTest extends AuthorizedControllerTest
{
    use ProphecyTrait;

    private const PRODUCT = "Capsa 12 Moixa Feresta";
    private const QUANTITY = 2;
    private const PRICE_IN_CENTS = 2500;
    private const VAT_PERCENT = 21;
    private const INVOICE_NUMBER = "2024/001";
    private const EMITTER_TAXNUMBER = "43186322G";
    private const EMITTER_TAXNAME = "EMITTER TAX NAME SL";
    private const EMITTER_RENDERED_TAXADDR = "Emitter Street 1, 07001";
    private const RECEIVER_TAXNAME = "RECEIVER TAX NAME SL";
    private const RECEIVER_TAXNUMBER = "B071135688";
    private const RECEIVER_RENDERED_TAXADDR = "Reception Street 1, 07002";
    private const INVOICE_DATE = '2024-10-31';
    private $usecase;

    public function setUp(): void
    {
        parent::setUp();
        $this->usecase = $this->prophesize(GetInvoicesUseCase::class);
    }

    public function test_fails_when_unauthorized(): void
    {
        $request = new Request();
        $controller = $this->getController();

        $this->expectException(InvalidCredentialsException::class);

        $controller($request);
    }

    public function test_builds_command_with_no_filters(): void
    {
        $request = new Request();
        $request->headers->set('Authorization', 'Bearer ' . self::TOKEN);

        $controller = $this->getController();
        $expectedCommand = new GetInvoicesCommand($this->user->accountId());
        $this->usecase->__invoke($expectedCommand)
            ->shouldBeCalled()
            ->willReturn(new ExposableInvoices([]));

        $controller($request);
    }

    public function test_builds_command_with_from_date(): void
    {
        $request = new Request();
        $request->headers->set('Authorization', 'Bearer ' . self::TOKEN);
        $request->query->set('from', '2024-01-01');

        $controller = $this->getController();
        $expectedCommand = new GetInvoicesCommand(
            accountId: $this->user->accountId(),
            fromDate: new DateTime('2024-01-01'),
        );
        $this->usecase->__invoke($expectedCommand)
            ->shouldBeCalled()
            ->willReturn(new ExposableInvoices([]));

        $controller($request);
    }

    public function test_builds_command_with_to_date(): void
    {
        $request = new Request();
        $request->headers->set('Authorization', 'Bearer ' . self::TOKEN);
        $request->query->set('to', '2024-01-01');

        $controller = $this->getController();
        $expectedCommand = new GetInvoicesCommand(
            accountId: $this->user->accountId(),
            toDate: new DateTime('2024-01-01'),
        );
        $this->usecase->__invoke($expectedCommand)
            ->shouldBeCalled()
            ->willReturn(new ExposableInvoices([]));

        $controller($request);
    }

    public function test_builds_command_with_emitted_by(): void
    {
        $request = new Request();
        $request->headers->set('Authorization', 'Bearer ' . self::TOKEN);
        $request->query->set('emitted_by', '43186322G');

        $controller = $this->getController();
        $expectedCommand = new GetInvoicesCommand(
            accountId: $this->user->accountId(),
            emitterTaxNumber: '43186322G',
        );
        $this->usecase->__invoke($expectedCommand)
            ->shouldBeCalled()
            ->willReturn(new ExposableInvoices([]));

        $controller($request);
    }

    public function test_builds_command_with_emitted_to(): void
    {
        $request = new Request();
        $request->headers->set('Authorization', 'Bearer ' . self::TOKEN);
        $request->query->set('received_by', '43186322G');

        $controller = $this->getController();
        $expectedCommand = new GetInvoicesCommand(
            accountId: $this->user->accountId(),
            receiverTaxNumber: '43186322G',
        );
        $this->usecase->__invoke($expectedCommand)
            ->shouldBeCalled()
            ->willReturn(new ExposableInvoices([]));

        $controller($request);
    }

    public function test_succeeds_with_no_results(): void
    {
        $request = new Request();
        $request->headers->set('Authorization', 'Bearer ' . self::TOKEN);
        $controller = $this->getController();
        $this->usecase->__invoke(Argument::type(GetInvoicesCommand::class))
            ->shouldBeCalled()
            ->willReturn(new ExposableInvoices([]));

        $response = $controller($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEmpty(json_decode($response->getContent(), true));
    }

    public function test_returns_expected_format(): void
    {
        $request = new Request();
        $request->headers->set('Authorization', 'Bearer ' . self::TOKEN);
        $controller = $this->getController();
        $invoice = $this->buildInvoice();
        $this->usecase->__invoke(Argument::type(GetInvoicesCommand::class))
            ->willReturn(new ExposableInvoices([$invoice]));

        $result = $controller($request);

        $decoded_result = json_decode($result->getContent(), true);
        $this->assertcount(1, $decoded_result);
        $result_invoice = $decoded_result[0];
        $this->assertEquals(self::INVOICE_NUMBER, $result_invoice['invoice_number']);
        $this->assertEquals(self::INVOICE_DATE, $result_invoice['invoice_date']);
        $this->assertEquals(self::EMITTER_TAXNAME, $result_invoice['emitter_tax_name']);
        $this->assertEquals(self::EMITTER_TAXNUMBER, $result_invoice['emitter_tax_number']);
        $this->assertEquals(self::EMITTER_RENDERED_TAXADDR, $result_invoice['emitter_tax_address']);
        $this->assertEquals(self::RECEIVER_TAXNAME, $result_invoice['receiver_tax_name']);
        $this->assertEquals(self::RECEIVER_TAXNUMBER, $result_invoice['receiver_tax_number']);
        $this->assertEquals(self::RECEIVER_RENDERED_TAXADDR, $result_invoice['receiver_tax_address']);
        $this->assertEquals("50.00 €", $result_invoice['base_amount']);
        $this->assertEquals("10.50 €", $result_invoice['vat_amount']);
        $this->assertEquals("60.50 €", $result_invoice['total_amount']);
    }

    private function getController(): GetInvoicesController
    {
        return new GetInvoicesController(
            $this->tokenDecoder->reveal(),
            $this->userRepository->reveal(),
            $this->usecase->reveal(),
        );
    }

    private function buildInvoice(): InvoiceAggregate
    {
        $invoice = new Invoice(
            new Id(1),
            new InvoiceNumber(self::INVOICE_NUMBER),
            new Id(244),
            new Id(255),
            new DateTime(self::INVOICE_DATE),
        );
        return new InvoiceAggregate(
            $invoice,
            [
                new InvoiceLine(
                    self::PRODUCT,
                    self::QUANTITY,
                    new Money(self::PRICE_IN_CENTS),
                    new Percentage(self::VAT_PERCENT)
                )
            ],
            new Business(
                new Id(244),
                "EMITTER INFORMAL NAME",
                self::EMITTER_TAXNAME,
                self::EMITTER_TAXNUMBER,
                new Address("Emitter Street 1", "07001"),
            ),
            new Business(
                new Id(255),
                "RECEIVER INFORMAL NAME",
                self::RECEIVER_TAXNAME,
                self::RECEIVER_TAXNUMBER,
                new Address("Reception Street 1", "07002"),
            ),
        );
    }
}