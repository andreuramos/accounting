<?php

namespace App\Domain\Entities;

use App\Domain\ValueObject\Id;
use App\Domain\ValueObject\InvoiceNumber;
use DateTime;

class Invoice
{
    public function __construct(
        public readonly Id $id,
        public readonly InvoiceNumber $invoiceNumber,
        public readonly Id $emitterBusinessId,
        public readonly Id $receiverBusinessId,
        public readonly DateTime $dateTime,
    ) {
    }

    public function wasEmittedInYear(int $currentYear): bool
    {
        return (int) $this->dateTime->format('Y') === $currentYear;
    }
}
