<?php

namespace App\Transaction\Application\Command;

use App\Shared\Domain\ValueObject\Id;

class GetAccountIncomesCommand
{
    public function __construct(
        public readonly Id $accountId,
    ) {
    }
}
