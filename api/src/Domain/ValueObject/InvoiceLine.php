<?php

namespace App\Domain\ValueObject;

class InvoiceLine
{
    public function __construct(
        public readonly string $product,
        public readonly int $quantity,
        public readonly Money $amount,
        public readonly Percentage $vat_percentage,
    ) {
    }
    
    public function baseAmount(): Money
    {
        return new Money($this->amount->amountCents * $this->quantity);
    }
    
    public function vatAmount(): Money
    {
        return new Money(
            $this->amount->amountCents * 
            $this->quantity *
            ($this->vat_percentage->value / 100)
        );
    }
    
    public function totalAmount(): Money
    {
        return new Money(
            $this->baseAmount()->amountCents +
            $this->vatAmount()->amountCents
        );
    }
}
