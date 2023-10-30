<?php

namespace Test\Unit\Application\UseCase;

use App\Application\UseCase\RenderInvoice\RenderInvoiceCommand;
use App\Application\UseCase\RenderInvoice\RenderInvoiceUseCase;
use App\Domain\Exception\InvoiceNotFoundException;
use App\Domain\Repository\InvoiceRepositoryInterface;
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
