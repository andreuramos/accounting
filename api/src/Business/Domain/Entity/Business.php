<?php

namespace App\Business\Domain\Entity;

use App\Business\Domain\ValueObject\Address;
use App\Shared\Domain\ValueObject\Id;

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
