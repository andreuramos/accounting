<?php

namespace App\Application\UseCase\GetAccountExpenses;

use App\Domain\ValueObject\Id;

class GetAccountExpensesCommand
{
    public function __construct(
        public readonly Id $accountId
    ) {
    }
}
