<?php

namespace App\Invoice\Domain\Entity;

use App\Invoice\Domain\ValueObject\InvoiceNumber;
use App\Shared\Domain\ValueObject\Id;
use App\Transaction\Domain\Entity\Income;
use DateTime;

class Invoice
{
    public function __construct(
        public readonly Id $id,
        public readonly InvoiceNumber $invoiceNumber,
        public readonly Id $incomeId,
        public readonly Business $emitter,
        public readonly Business $receiver,
        public readonly DateTime $dateTime,
    ) {
    }
}
