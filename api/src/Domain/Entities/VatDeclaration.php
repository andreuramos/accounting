<?php

namespace App\Domain\Entities;

use App\Domain\ValueObject\DeclarationPeriod;

class VatDeclaration
{
    public function __construct(
        public readonly string $taxNumber,
        public readonly string $taxName,
        public readonly int $year,
        public readonly DeclarationPeriod $period,
    ) {
    }
}
