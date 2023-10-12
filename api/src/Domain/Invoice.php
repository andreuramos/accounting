<?php

namespace App\Domain;

use DateTime;

class Invoice
{
    public function __construct(
        public readonly Id $id,
        public readonly InvoiceNumber $invoiceNumber,
        public readonly Id $incomeId,
        public readonly Id $emitterBusinessId,
        public readonly Id $receiverBusinessId,
        public readonly DateTime $dateTime,
    ) {
    }
}
