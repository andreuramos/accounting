<?php

namespace App\Application\UseCase\RenderInvoice;

use App\Domain\Repository\InvoiceAggregateRepositoryInterface;
use App\Domain\Service\FileSaverInterface;
use App\Domain\Service\InvoiceRendererInterface;
use App\Domain\ValueObject\Id;
use App\Domain\ValueObject\InvoiceNumber;

class RenderInvoiceUseCase
{
    public function __construct(
        private readonly InvoiceAggregateRepositoryInterface $invoiceRepository,
        private readonly InvoiceRendererInterface $invoiceRenderer,
        private readonly FileSaverInterface $fileSaver,
    ) {
    }

    public function __invoke(RenderInvoiceCommand $command): void
    {
        $invoice = $this->invoiceRepository->findByBusinessIdAndNumber(
            new Id(1),
            new InvoiceNumber($command->invoiceNumber),
        );

        $renderedInvoiceContent = ($this->invoiceRenderer)($invoice);

        $invoiceFileName = $command->accountId . "-" . $command->invoiceNumber . ".pdf";
        ($this->fileSaver)($renderedInvoiceContent, $invoiceFileName);
    }
}
