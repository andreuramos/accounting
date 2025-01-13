<?php

namespace Test\Unit\Application\UseCase;

use App\Application\UseCase\RenderInvoice\RenderInvoiceCommand;
use App\Application\UseCase\RenderInvoice\RenderInvoiceUseCase;
use App\Domain\Entities\Invoice;
use App\Domain\Entities\InvoiceAggregate;
use App\Domain\Exception\InvoiceNotFoundException;
use App\Domain\Repository\InvoiceAggregateRepositoryInterface;
use App\Domain\Service\InvoiceRendererInterface;
use App\Domain\ValueObject\InvoiceLine;
use App\Domain\ValueObject\Percentage;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;

class RenderInvoiceUseCaseTest extends TestCase
{
    use ProphecyTrait;

    private $invoiceAggregateRepository;
    private $invoiceRenderer;

    public function setUp(): void
    {
        $this->invoiceAggregateRepository = $this->prophesize(InvoiceAggregateRepositoryInterface::class);
        $this->invoiceRenderer = $this->prophesize(InvoiceRendererInterface::class);
    }

    public function test_throws_exception_when_invoice_not_found(): void
    {
        $command = new RenderInvoiceCommand(1,"2023001");
        $this->invoiceAggregateRepository->findByBusinessIdAndNumber(Argument::cetera())
            ->willThrow(InvoiceNotFoundException::class);
        $useCase = $this->buildUseCase();

        $this->expectException(InvoiceNotFoundException::class);

        $useCase($command);
    }
    
    public function test_renders_invoice(): void
    {
        $command = new RenderInvoiceCommand(1,"2023001");
        $invoiceAggregate = $this->createMock(InvoiceAggregate::class);
        $this->invoiceAggregateRepository
            ->findByBusinessIdAndNumber(Argument::cetera())
            ->willReturn($invoiceAggregate);
        
        $this->invoiceRenderer->__invoke($invoiceAggregate)->shouldBeCalled();
        
        $useCase = $this->buildUseCase();
        $useCase($command);
    }

    private function buildUseCase()
    {
        return new RenderInvoiceUseCase(
            $this->invoiceAggregateRepository->reveal(),
            $this->invoiceRenderer->reveal(),
        );
    }
}
