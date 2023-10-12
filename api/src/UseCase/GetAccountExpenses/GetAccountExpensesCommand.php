<?php

namespace App\UseCase\GetAccountExpenses;

use App\Domain\Id;

class GetAccountExpensesCommand
{
    public function __construct(
        public readonly Id $accountId
    ) {
    }
}
