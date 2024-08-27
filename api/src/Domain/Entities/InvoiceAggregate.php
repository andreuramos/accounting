<?php

namespace App\Domain\Entities;

use App\Domain\Exception\InvalidArgumentException;
use App\Domain\ValueObject\Id;
use App\Domain\ValueObject\Money;

class InvoiceAggregate
{
    private Money $totalAmount;

    public function __construct(
        private readonly Invoice $invoice,
        private readonly array $invoiceLines,
    ) {
        $this->guardInvoiceLines($this->invoiceLines);
        $this->totalAmount = $this->aggregateLinesAmount($this->invoiceLines);
    }

    public function id(): Id
    {
        return $this->invoice->id;
    }

    public function invoice(): Invoice
    {
        return $this->invoice;
    }

    public function invoiceLines(): array
    {
        return $this->invoiceLines;
    }

    public function totalAmount(): Money
    {
        return $this->totalAmount;
    }

    private function guardInvoiceLines(array $invoiceLines): void
    {
        if (empty($invoiceLines)) {
            throw new InvalidArgumentException("invoice lines", 'cannot be empty');
        }
        foreach ($invoiceLines as $invoiceLine) {
            if (!($invoiceLine instanceof InvoiceLine)) {
                throw new InvalidArgumentException(
                    "invoice lines",
                    "is not an InvoiceLine instance"
                );
            }
        }
    }

    private function aggregateLinesAmount(array $invoiceLines): Money
    {
        $totalAmountCents = array_reduce($invoiceLines, function ($accumulated, InvoiceLine $invoiceLine) {
            return $accumulated + $invoiceLine->amount->amountCents;
        });

        return new Money($totalAmountCents);
    }
}
