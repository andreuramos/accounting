<?php

namespace App\Domain\Entities;

use App\Domain\Exception\InvalidArgumentException;
use App\Domain\ValueObject\Id;
use App\Domain\ValueObject\InvoiceLine;
use App\Domain\ValueObject\InvoiceNumber;
use App\Domain\ValueObject\Money;
use DateTime;

class InvoiceAggregate
{
    private Money $baseAmount;
    private Money $vatAmount;

    public function __construct(
        private readonly Invoice $invoice,
        private readonly array $invoiceLines,
        private readonly Business $emitterBusiness,
        private readonly Business $receiverBusiness,
    ) {
        $this->guardInvoiceLines($this->invoiceLines);
        [$this->baseAmount, $this->vatAmount] = $this->aggregateLinesAmount($this->invoiceLines);
    }

    public function id(): Id
    {
        return $this->invoice->id;
    }

    public function invoiceNumber(): InvoiceNumber
    {
        return $this->invoice->invoiceNumber;
    }

    public function invoiceDate(): DateTime
    {
        return $this->invoice->dateTime;
    }

    public function baseAmount(): Money
    {
        return $this->baseAmount;
    }

    public function vatAmount(): Money
    {
        return $this->vatAmount;
    }

    public function totalAmount(): Money
    {
        return new Money(
            $this->baseAmount->amountCents +
            $this->vatAmount->amountCents
        );
    }

    public function emitterTaxName(): string
    {
        return $this->emitterBusiness->taxName;
    }

    public function emitterTaxNumber(): string
    {
        return $this->emitterBusiness->taxNumber;
    }

    public function emitterTaxAddress(): string
    {
        return (string)$this->emitterBusiness->taxAddress;
    }

    public function receiverTaxName(): string
    {
        return $this->receiverBusiness->taxName;
    }

    public function receiverTaxNumber(): string
    {
        return $this->receiverBusiness->taxNumber;
    }

    public function receiverTaxAddress(): string
    {
        return (string)$this->receiverBusiness->taxAddress;
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
        foreach ($invoiceLines as $invoiceLine) {
            if (!($invoiceLine instanceof InvoiceLine)) {
                throw new InvalidArgumentException(
                    "invoice lines",
                    "is not an InvoiceLine instance"
                );
            }
        }
    }

    private function aggregateLinesAmount(array $invoiceLines): array
    {
        $baseAmount = array_reduce($invoiceLines, function ($accumulated, InvoiceLine $invoiceLine) {
            $accumulated['base'] += $invoiceLine->amount->amountCents;
            $accumulated['vat'] += round($invoiceLine->amount->amountCents * $invoiceLine->vat_percentage->value / 100);
            return $accumulated;
        }, ['base' => 0, 'vat' => 0]);

        return [
            new Money($baseAmount['base']),
            new Money($baseAmount['vat']),
        ];
    }
}
