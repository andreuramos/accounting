<?php

namespace App\Domain;

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
