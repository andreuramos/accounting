<?php

namespace App\Transaction\Application\Command;

use App\User\Domain\Entity\User;

class GetUserIncomesCommand
{
    public function __construct(
        public readonly User $user
    ) {
    }
}
