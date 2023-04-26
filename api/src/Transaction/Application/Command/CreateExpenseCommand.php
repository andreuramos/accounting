<?php

namespace App\Transaction\Application\Command;

class CreateExpenseCommand
{
    public function __construct(
        public readonly int $amountCents,
        public readonly string $description,
        public readonly string $date
    ) {
    }
}
