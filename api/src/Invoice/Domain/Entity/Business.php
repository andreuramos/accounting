<?php

namespace App\Invoice\Domain\Entity;

use App\Shared\Domain\ValueObject\Id;
use App\Tax\Domain\Entity\TaxData;

class Business
{
    public function __construct(
        public readonly Id $id,
        public readonly string $name,
        public readonly TaxData $taxData,
    ) {
    }
}
