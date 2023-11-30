<?php

namespace App\Domain\ValueObject;

class DeductibleTax
{
    public function __construct(
        public readonly int $base,
        public readonly int $tax,
    ) {
    }
}
