<?php

namespace App\Transaction\Domain\Entity;

use App\Shared\Domain\ValueObject\Id;
use App\Transaction\Domain\ValueObject\Money;

class Expense
{
    public function __construct(
        private readonly Id $userId,
        private readonly Money $amount,
        private readonly string $description,
        private readonly \DateTime $date
    ) {
    }
}
