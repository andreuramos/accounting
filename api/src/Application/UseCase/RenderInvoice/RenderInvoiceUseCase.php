<?php

namespace App\Application\UseCase\RenderInvoice;

use App\Domain\Repository\InvoiceRepositoryInterface;
use App\Domain\ValueObject\Id;
use App\Domain\ValueObject\InvoiceNumber;

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
