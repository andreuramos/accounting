<?php

namespace App\UseCase\EmitInvoice;

use App\Domain\User;

class EmitInvoiceCommand
{
    public readonly int $invoiceAmount;

    public function __construct(
        public readonly User $user,
        public readonly string $customerName,
        public readonly string $customerTaxName,
        public readonly string $customerTaxNumber,
        public readonly string $customerTaxAddress,
        public readonly string $customerTaxZipCode,
        public readonly \DateTime $date,
        public readonly array $invoiceLines,
    ) {
        $invoiceAmount = 0;
        foreach ($this->invoiceLines as $line) {
            $invoiceAmount += $line['amount'];
        }
        $this->invoiceAmount = $invoiceAmount;
    }
}
