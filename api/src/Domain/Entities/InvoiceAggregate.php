<?php

namespace App\Domain\Entities;

use App\Domain\ValueObject\Id;
use App\Domain\ValueObject\InvoiceNumber;

class InvoiceAggregate
{
    public function __construct(
        private readonly Invoice $invoice,
        private readonly array $invoiceLines,
    ) {
    }

    public function invoice(): Invoice
    {
        return $this->invoice;
    }

    public function invoiceLines(): array
    {
        return $this->invoiceLines;
    }
}
