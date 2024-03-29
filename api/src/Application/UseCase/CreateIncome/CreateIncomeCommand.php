<?php

namespace App\Application\UseCase\CreateIncome;

use App\Domain\Entities\User;
use App\Domain\ValueObject\Id;

class CreateIncomeCommand
{
    public function __construct(
        public readonly User $user,
        public readonly Id $accountId,
        public readonly int $amountCents,
        public readonly string $description,
        public readonly string $date,
    ) {
    }
}
