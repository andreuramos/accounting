<?php

namespace App\Domain\Entities;

use App\Domain\Exception\InvalidArgumentException;

class InvoiceAggregate
{
    public function __construct(
        private readonly Invoice $invoice,
        private readonly array $invoiceLines,
    ) {
        $this->guardInvoiceLines($this->invoiceLines);
    }

    public function invoice(): Invoice
    {
        return $this->invoice;
    }

    public function invoiceLines(): array
    {
        return $this->invoiceLines;
    }

    private function guardInvoiceLines(array $invoiceLines): void
    {
        if (empty($invoiceLines)) {
            throw new InvalidArgumentException("invoice lines", 'cannot be empty');
        }
    }
}
