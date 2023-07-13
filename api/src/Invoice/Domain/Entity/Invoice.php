<?php

namespace App\Invoice\Domain\Entity;

use App\Invoice\Domain\ValueObject\InvoiceNumber;
use App\Shared\Domain\ValueObject\Id;
use DateTime;
use http\Exception\InvalidArgumentException;

class Invoice
{
    public function __construct(
        public readonly Id $id,
        public readonly InvoiceNumber $invoiceNumber,
        public readonly Id $incomeId,
        public readonly Id $emitterBusinessId,
        public readonly Id $receiverBusinessId,
        public readonly DateTime $dateTime,
        public readonly array $invoiceLines
    ) {
        $this->guardLines($this->invoiceLines);
    }

    private function guardLines(array $invoiceLines): void
    {
        foreach ($invoiceLines as $line) {
            if (!$line instanceof InvoiceLine) {
                throw new InvalidArgumentException();
            }
        }
    }
}
