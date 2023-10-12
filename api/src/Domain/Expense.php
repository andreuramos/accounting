<?php

namespace App\Domain;

class Expense
{
    public function __construct(
        public readonly Id $id,
        public readonly Id $accountId,
        public readonly Money $amount,
        public readonly string $description,
        public readonly \DateTime $date
    ) {
    }
}
