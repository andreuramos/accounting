<?php

namespace App\Transaction\Domain\ValueObject;

class Money
{
    const VALID_CURRENCIES = ['EUR'];

    public function __construct(
        public readonly int $amountCents,
        public readonly string $currency
    ) {
        $this->guardCurrency($this->currency);
    }

    private function guardCurrency(string $currency): void
    {
        if(!in_array($currency, self::VALID_CURRENCIES)) {
            throw new \InvalidArgumentException("invalid currency " . $currency);
        }
    }
}
