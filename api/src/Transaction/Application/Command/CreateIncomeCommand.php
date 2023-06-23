<?php

namespace App\Transaction\Application\Command;

use App\Shared\Domain\ValueObject\Id;
use App\User\Domain\Entity\User;

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
