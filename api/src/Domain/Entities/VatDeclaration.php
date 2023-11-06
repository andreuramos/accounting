<?php

namespace App\Domain\Entities;

class VatDeclaration
{
    public function __construct(
        public readonly string $taxNumber,
        public readonly string $taxName,
        public readonly int $year,
        public readonly int $period,
    ) {
    }
}
