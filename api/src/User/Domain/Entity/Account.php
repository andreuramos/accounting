<?php

namespace App\User\Domain\Entity;

use App\Shared\Domain\ValueObject\Id;

class Account
{
    public function __construct(
        private readonly Id $id,
        private readonly Id $userId,
    ) {
    }
}
