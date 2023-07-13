<?php

namespace App\Invoice\Domain\Entity;

use App\Transaction\Domain\ValueObject\Money;

class InvoiceLine
{
    public function __construct(
        public readonly string $product,
        public readonly int $quantity,
        public readonly Money $amount,
    ) {
    }
}
