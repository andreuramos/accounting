<?php

namespace App\Invoice\Application\UseCase;

use App\Invoice\Application\Command\RenderInvoiceCommand;
use App\Invoice\Domain\Model\InvoiceRepositoryInterface;
use App\Invoice\Domain\ValueObject\InvoiceNumber;
use App\Shared\Domain\ValueObject\Id;

class RenderInvoiceUseCase
{
    public function __construct(private readonly InvoiceRepositoryInterface $invoiceRepository)
    {
    }

    public function __invoke(RenderInvoiceCommand $command): void
    {
        $invoice = $this->invoiceRepository->findByBusinessIdAndNumber(
            new Id(1),
            new InvoiceNumber($command->invoiceNumber),
        );
    }
}
