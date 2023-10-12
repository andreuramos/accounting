<?php

namespace App\UseCase\GetAccountIncomes;

use App\Domain\Id;

class GetAccountIncomesCommand
{
    public function __construct(
        public readonly Id $accountId,
    ) {
    }
}
