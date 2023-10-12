<?php

namespace App\Domain;

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
