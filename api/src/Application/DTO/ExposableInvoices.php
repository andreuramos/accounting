<?php

namespace App\Application\DTO;

use App\Domain\Entities\InvoiceAggregate;

class ExposableInvoices
{
    private array $exposableInvoiceData = [];

    public function __construct(array $invoices)
    {
        /** @var InvoiceAggregate $invoice */
        foreach ($invoices as $invoice) {
            $this->exposableInvoiceData[] = [
                "invoice_number" => $invoice->invoiceNumber()->number,
                "emitter_tax_name" => $invoice->emitterTaxName(),
                "emitter_tax_number" => $invoice->emitterTaxNumber(),
                "emitter_tax_address" => $invoice->emitterTaxAddress(),
                "receiver_tax_name" => $invoice->receiverTaxName(),
                "receiver_tax_number" => $invoice->receiverTaxNumber(),
                "receiver_tax_address" => $invoice->receiverTaxAddress(),
                "base_amount" => (string) $invoice->baseAmount(),
                "vat_amount" => (string) $invoice->vatAmount(),
                "total_amount" => (string) $invoice->totalAmount(),
            ];
        }
    }

    public function __toArray(): array
    {
        return $this->exposableInvoiceData;
    }
}
