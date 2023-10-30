<?php

namespace App\Application\UseCase\GetAccountIncomes;

use App\Domain\ValueObject\Id;

class GetAccountIncomesCommand
{
    public function __construct(
        public readonly Id $accountId,
    ) {
    }
}
