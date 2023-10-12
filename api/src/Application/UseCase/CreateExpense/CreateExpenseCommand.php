<?php

namespace App\Application\UseCase\CreateExpense;

use App\Domain\ValueObject\Id;

class CreateExpenseCommand
{
    public function __construct(
        public readonly int $amountCents,
        public readonly string $description,
        public readonly string $date,
        public readonly Id $userId,
        public readonly Id $accountId,
    ) {
    }
}
