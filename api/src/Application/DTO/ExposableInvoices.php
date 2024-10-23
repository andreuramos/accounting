<?php

namespace App\Application\DTO;

use App\Domain\Entities\InvoiceAggregate;

class ExposableInvoices
{
    private array $exposableInvoices = [];

    public function __construct(array $invoices)
    {
        /** @var InvoiceAggregate $invoice */
        foreach ($invoices as $invoice) {
            $this->exposableInvoices[] = [
                "invoice_number" => $invoice->invoiceNumber()->number,
            ];
        }
    }

    public function __toArray(): array
    {
        return $this->exposableInvoices;
    }
}
