<?php

namespace App\Domain\Entities;

use App\Domain\ValueObject\Id;

class Account
{
    public function __construct(
        public readonly Id $id,
        public readonly Id $mainUserId,
    ) {
    }
}
