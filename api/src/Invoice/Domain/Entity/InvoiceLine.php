<?php

namespace App\Invoice\Domain\Entity;

use App\Shared\Domain\ValueObject\Id;
use App\Transaction\Domain\ValueObject\Money;

class InvoiceLine
{
    public function __construct(
        public readonly Id $invoiceId,
        public readonly string $product,
        public readonly int $quantity,
        public readonly Money $amount,
    ) {
    }
}
