<?php

namespace App\Application\UseCase\ReceiveInvoice;

use App\Domain\Entities\Invoice;
use App\Domain\Exception\InvoiceAlreadyExistsException;
use App\Domain\Repository\InvoiceRepositoryInterface;
use App\Domain\ValueObject\InvoiceNumber;

class ReceiveInvoiceUseCase
{
    public function __construct(
        private readonly InvoiceRepositoryInterface $invoiceRepository
    ) {
    }
    
    public function __invoke(ReceiveInvoiceCommand $command): void
    {
        $this->guardInvoiceDoesNotYetExist($command);
    }

    private function guardInvoiceDoesNotYetExist(ReceiveInvoiceCommand $command): void
    {
        $invoiceNumber = new InvoiceNumber($command->invoice_number);
        $invoice = $this->invoiceRepository->findByEmitterTaxNumberAndInvoiceNumber(
            $command->provider_tax_number,
            $invoiceNumber,
        );
        
        if ($invoice instanceof Invoice) {
            throw new InvoiceAlreadyExistsException(
                $command->provider_tax_number, 
                $invoiceNumber,
            );
        }
    }
}