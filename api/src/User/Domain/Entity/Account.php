<?php

namespace App\User\Domain\Entity;

use App\Shared\Domain\ValueObject\Id;

class Account
{
    public function __construct(
        public readonly Id $id,
        public readonly Id $mainUserId,
    ) {
    }
}
