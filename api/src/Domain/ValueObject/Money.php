<?php

namespace App\Domain\ValueObject;

class Money
{
    private const VALID_CURRENCIES = ['EUR'];

    public function __construct(
        public readonly int $amountCents,
        public readonly string $currency = 'EUR'
    ) {
        $this->guardCurrency($this->currency);
    }

    private function guardCurrency(string $currency): void
    {
        if (!in_array($currency, self::VALID_CURRENCIES)) {
            throw new \InvalidArgumentException("invalid currency " . $currency);
        }
    }
}
