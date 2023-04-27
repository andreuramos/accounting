<?php

namespace App\Transaction\Application\Command;

use App\Shared\Domain\ValueObject\Id;

class CreateExpenseCommand
{
    public function __construct(
        public readonly int $amountCents,
        public readonly string $description,
        public readonly string $date,
        public readonly Id $userId
    ) {
    }
}
