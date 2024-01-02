<?php

namespace App\Application\UseCase\Form303;

class Manual303FormCommand
{
    public function __construct(
        public readonly string $tax_name,
        public readonly string $tax_number,
        public readonly int $year,
        public readonly int $quarter,
        public readonly int $accrued_base,
        public readonly int $accrued_tax,
        public readonly int $deductible_base,
        public readonly int $deductible_tax,
        public readonly string $iban,
    ) {
    }
}