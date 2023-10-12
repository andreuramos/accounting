<?php

namespace App\Domain;

class Account
{
    public function __construct(
        public readonly Id $id,
        public readonly Id $mainUserId,
    ) {
    }
}
