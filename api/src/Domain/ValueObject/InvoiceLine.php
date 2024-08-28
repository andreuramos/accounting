<?php

namespace App\Domain\ValueObject;

class InvoiceLine
{
    public function __construct(
        public readonly string $product,
        public readonly int $quantity,
        public readonly Money $amount,
    ) {
    }
}
