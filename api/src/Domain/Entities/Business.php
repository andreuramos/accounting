<?php

namespace App\Domain\Entities;

use App\Domain\ValueObject\Address;
use App\Domain\ValueObject\Id;

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
