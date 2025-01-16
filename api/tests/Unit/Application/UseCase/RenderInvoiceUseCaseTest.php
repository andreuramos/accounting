<?php

namespace Test\Unit\Application\UseCase;

use App\Application\UseCase\RenderInvoice\RenderInvoiceCommand;
use App\Application\UseCase\RenderInvoice\RenderInvoiceUseCase;
use App\Domain\Entities\Invoice;
use App\Domain\Entities\InvoiceAggregate;
use App\Domain\Exception\InvoiceNotFoundException;
use App\Domain\Repository\InvoiceAggregateRepositoryInterface;
use App\Domain\Service\FileSaverInterface;
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
    private $fileSaver;

    public function setUp(): void
    {
        $this->invoiceAggregateRepository = $this->prophesize(InvoiceAggregateRepositoryInterface::class);
        $this->invoiceRenderer = $this->prophesize(InvoiceRendererInterface::class);
        $this->fileSaver = $this->prophesize(FileSaverInterface::class);
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
    
    public function test_uses_renderer_to_generate_the_file(): void
    {
        $command = new RenderInvoiceCommand(1,"2023001");
        $invoiceAggregate = $this->createMock(InvoiceAggregate::class);
        $this->invoiceAggregateRepository
            ->findByBusinessIdAndNumber(Argument::cetera())
            ->willReturn($invoiceAggregate);
        $this->fileSaver->__invoke(Argument::cetera())
            ->willReturn("file/url.pdf");
        
        $this->invoiceRenderer->__invoke($invoiceAggregate)
            ->shouldBeCalled()
            ->willReturn("filecontents");
        
        $useCase = $this->buildUseCase();
        $useCase($command);
    }
    
    public function test_saves_invoice_file(): void
    {
        $command = new RenderInvoiceCommand(1,"2025001");
        $invoiceAggregate = $this->createMock(InvoiceAggregate::class);
        $fileContents = "filecontent";
        $this->invoiceAggregateRepository->findByBusinessIdAndNumber(Argument::cetera())
            ->willReturn($invoiceAggregate);
        $this->invoiceRenderer->__invoke(Argument::cetera())
            ->willReturn($fileContents);

        $this->fileSaver->__invoke($fileContents, "1-2025001.pdf")
            ->shouldBeCalled()
            ->willReturn("url/to/1-2025001.pdf");
        
        $useCase = $this->buildUseCase();
        $useCase($command);
    }
    
    public function test_saves_file_route_to_aggregate(): void
    {
        $this->markTestIncomplete('not implemented');
    }

    private function buildUseCase()
    {
        return new RenderInvoiceUseCase(
            $this->invoiceAggregateRepository->reveal(),
            $this->invoiceRenderer->reveal(),
            $this->fileSaver->reveal(),
        );
    }
}
