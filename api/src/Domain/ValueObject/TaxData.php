<?php

namespace App\Domain\ValueObject;

class TaxData
{
    public function __construct(
        public readonly Id $userId,
        public string $taxName,
        public string $taxNumber,
        public Address $address
    ) {
        if (!$this->taxName) {
            throw new \InvalidArgumentException($this->taxName);
        }
    }
}
