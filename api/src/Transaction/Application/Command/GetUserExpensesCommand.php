<?php

namespace App\Transaction\Application\Command;

use App\User\Domain\Entity\User;

class GetUserExpensesCommand
{
    public function __construct(
        public readonly User $user
    ) {
    }
}
