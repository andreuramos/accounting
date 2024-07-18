<?php

namespace App\Application\UseCase\ReceiveInvoice;

use App\Domain\Entities\Business;
use App\Domain\Entities\Invoice;
use App\Domain\Entities\InvoiceLine;
use App\Domain\Exception\InvoiceAlreadyExistsException;
use App\Domain\Repository\BusinessRepositoryInterface;
use App\Domain\Repository\InvoiceLineRepositoryInterface;
use App\Domain\Repository\InvoiceRepositoryInterface;
use App\Domain\ValueObject\Id;
use App\Domain\ValueObject\InvoiceNumber;
use App\Domain\ValueObject\Money;

class ReceiveInvoiceUseCase
{
    public function __construct(
        private readonly InvoiceRepositoryInterface $invoiceRepository,
        private readonly BusinessRepositoryInterface $businessRepository,
        private readonly InvoiceLineRepositoryInterface $invoiceLineRepository,
    ) {
    }

    public function __invoke(ReceiveInvoiceCommand $command): void
    {
        $this->guardInvoiceDoesNotYetExist($command);
        $receiver_business = $this->businessRepository->getByUserIdOrFail($command->user->id());
        $emitter_business = $this->getEmitterBusiness($command);
        
        $invoice = new Invoice(
            new Id(null),
            new InvoiceNumber($command->invoice_number),
            $emitter_business->id,
            $receiver_business->id,
            new \DateTime($command->date),
        );
        $invoice_id = $this->invoiceRepository->save($invoice);
        
        $invoice_line = new InvoiceLine(
            $invoice_id,
            $command->description,
            1,
            new Money($command->amount),
        );
        $this->invoiceLineRepository->save($invoice_line);
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

    private function getEmitterBusiness(ReceiveInvoiceCommand $command): Business
    {
        return $this->businessRepository->getByTaxNumber($command->provider_tax_number);
    }
}
