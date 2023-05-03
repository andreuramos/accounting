<?php

namespace App\Transaction\Application\Command;

use App\User\Domain\Entity\User;

class CreateIncomeCommand
{
    public function __construct(
        public readonly User $user,
        public readonly int $amountCents,
        public readonly string $description,
        public readonly string $date,
    ) {
    }
}
