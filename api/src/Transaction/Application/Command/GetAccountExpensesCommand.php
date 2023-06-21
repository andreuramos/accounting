<?php

namespace App\Transaction\Application\Command;

use App\Shared\Domain\ValueObject\Id;

class GetAccountExpensesCommand
{
    public function __construct(
        public readonly Id $accountId
    ) {
    }
}
