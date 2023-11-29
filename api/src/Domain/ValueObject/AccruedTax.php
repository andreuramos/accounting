<?php

namespace App\Domain\ValueObject;

class AccruedTax
{
    public function __construct(
        public readonly int $base,
        public readonly int $rate,
        public readonly int $tax,
    ) {
    }
}