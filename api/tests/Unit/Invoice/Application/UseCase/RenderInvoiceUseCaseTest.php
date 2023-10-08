<?php

namespace Test\Unit\Invoice\Application\UseCase;

use App\Invoice\Application\Command\RenderInvoiceCommand;
use App\Invoice\Application\UseCase\RenderInvoiceUseCase;
use App\Invoice\Domain\Exception\InvoiceNotFoundException;
use App\Invoice\Domain\Model\InvoiceRepositoryInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;

class RenderInvoiceUseCaseTest extends TestCase
{
    use ProphecyTrait;

    private $invoiceRepository;

    public function setUp(): void
    {
        $this->invoiceRepository = $this->prophesize(InvoiceRepositoryInterface::class);
    }

    public function test_throws_exception_when_invoice_not_found(): void
    {
        $command = new RenderInvoiceCommand(1,"2023001");
        $this->invoiceRepository->findByBusinessIdAndNumber(Argument::cetera())
            ->willThrow(InvoiceNotFoundException::class);
        $useCase = $this->buildUseCase();

        $this->expectException(InvoiceNotFoundException::class);

        $useCase($command);
    }

    private function buildUseCase()
    {
        return new RenderInvoiceUseCase(
            $this->invoiceRepository->reveal(),
        );
    }
}
