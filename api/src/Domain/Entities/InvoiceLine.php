<?php

namespace App\Domain\Entities;

use App\Domain\ValueObject\Id;
use App\Domain\ValueObject\Money;

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
