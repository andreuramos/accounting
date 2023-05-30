<?php

namespace App\Invoice\Domain\Entity;

use App\Shared\Domain\ValueObject\Id;
use App\Tax\Domain\Entity\TaxData;
use App\Tax\Domain\ValueObject\Address;

class Business
{
    public function __construct(
        public readonly Id $id,
        public readonly string $name,
        public readonly string $taxName,
        public readonly string $taxNumber,
        public readonly Address $taxAddress,
    ) {
    }
}
