<?php

namespace Test\Unit\Invoice\Infrastructure\Command;

use App\Invoice\Application\Command\RenderInvoiceCommand as ApplicationCommand;
use App\Invoice\Application\UseCase\RenderInvoiceUseCase;
use App\Invoice\Domain\Exception\InvoiceNotFoundException;
use App\Invoice\Infrastructure\Command\RenderInvoiceCommand;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;

class RenderInvoiceCommandTest extends TestCase
{
    use ProphecyTrait;

    private $renderInvoiceUseCase;

    public function setUp(): void
    {
        $this->renderInvoiceUseCase = $this->prophesize(RenderInvoiceUseCase::class);
    }

    public function test_null_account_returns_invalid(): void
    {
        $input = new ArrayInput([
            'accountId' => null,
            'invoiceNumber' => 123,
        ]);
        $command = $this->buildCommand();

        $resultStatusCode = $command->run($input, new NullOutput());

        $this->assertEquals(Command::INVALID, $resultStatusCode);
    }

    public function test_null_invoice_returns_invalid(): void
    {
        $input = new ArrayInput([
            'accountId' => 1,
            'invoiceNumber' => null,
        ]);
        $command = $this->buildCommand();

        $resultStatusCode = $command->run($input, new NullOutput());

        $this->assertEquals(Command::INVALID, $resultStatusCode);
    }

    public function test_failed_usecase_returns_failure(): void
    {
        $input = new ArrayInput([
            'accountId' => 1,
            'invoiceNumber' => "202300001",
        ]);
        $this->renderInvoiceUseCase->__invoke(Argument::type(ApplicationCommand::class))
            ->willThrow(InvoiceNotFoundException::class);
        $command = $this->buildCommand();

        $resultStatusCode = $command->run($input, new NullOutput());

        $this->assertEquals(Command::FAILURE, $resultStatusCode);
    }

    private function buildCommand(): RenderInvoiceCommand
    {
        return new RenderInvoiceCommand($this->renderInvoiceUseCase->reveal());
    }
}
