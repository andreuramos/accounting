<?php

namespace App\Transaction\Domain\Entity;

use App\Shared\Domain\ValueObject\Id;
use App\Transaction\Domain\ValueObject\Money;

class Income
{
    public function __construct(
        public readonly Id $id,
        public readonly Id $userId,
        public readonly Money $amount,
        public readonly string $description,
        public readonly \DateTime $date,
    ) {
    }
}
