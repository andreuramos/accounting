<?php

namespace App\Invoice\Domain\ValueObject;

class InvoiceNumber
{
    public function __construct(
        public readonly string $number
    ) {
    }

    public function __toString(): string
    {
        return $this->number;
    }
}
