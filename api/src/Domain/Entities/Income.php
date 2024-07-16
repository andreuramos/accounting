<?php

namespace App\Domain\Entities;

use App\Domain\ValueObject\Id;
use App\Domain\ValueObject\Money;

class Income
{
    public function __construct(
        public readonly Id $id,
        public readonly Id $accountId,
        public readonly Money $amount,
        public readonly string $description,
        public readonly \DateTime $date,
        public ?Id $invoiceId = null,
    ) {
    }
}
