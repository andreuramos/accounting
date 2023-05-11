<?php

namespace App\Tax\Domain\Aggregate;

use App\Shared\Domain\ValueObject\Id;
use App\Tax\Domain\ValueObject\Address;

class TaxDataAggregate
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
