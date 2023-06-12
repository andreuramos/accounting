<?php

namespace App\Business\Domain\Entity;

use App\Business\Domain\ValueObject\Address;
use App\Shared\Domain\ValueObject\Id;

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
