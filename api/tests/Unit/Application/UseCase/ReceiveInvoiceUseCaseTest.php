<?php

namespace Test\Unit\Application\UseCase;

use App\Application\UseCase\ReceiveInvoice\ReceiveInvoiceCommand;
use App\Application\UseCase\ReceiveInvoice\ReceiveInvoiceUseCase;
use App\Domain\Entities\Invoice;
use App\Domain\Exception\InvoiceAlreadyExistsException;
use App\Domain\Repository\InvoiceRepositoryInterface;
use App\Domain\ValueObject\InvoiceNumber;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class ReceiveInvoiceUseCaseTest extends TestCase
{
    use ProphecyTrait;
    
    private $invoiceRepository;
    
    public function setUp(): void
    {
        $this->invoiceRepository = $this->prophesize(InvoiceRepositoryInterface::class);
    }

    public function test_fails_if_invoice_already_exists(): void
    {
        $command = new ReceiveInvoiceCommand(
            "Brewery",
            "Brewery SK",
            "B076546546",
            "Camp Llarg 20",
            "07130",
            "20230000232",
            "",
            "2024-01-06",
            3000_00,
            600_00,
        );
        $this->invoiceRepository
            ->findByEmitterTaxNumberAndInvoiceNumber("B076546546", new InvoiceNumber("20230000232"))
            ->shouldBeCalled()
            ->willReturn($this->createMock(Invoice::class));
        $useCase = $this->buildUseCase();
        
        $this->expectException(InvoiceAlreadyExistsException::class);
        
        $useCase($command);
    }

    private function buildUseCase(): ReceiveInvoiceUseCase
    {
        return new ReceiveInvoiceUseCase(
            $this->invoiceRepository->reveal(),
        );
    }
}