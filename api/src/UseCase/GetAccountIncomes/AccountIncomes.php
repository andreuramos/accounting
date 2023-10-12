<?php

namespace App\UseCase\GetAccountIncomes;

use App\Domain\Income;

class AccountIncomes
{
    public function __construct(
        public readonly array $incomes
    ) {
        foreach ($incomes as $income) {
            if (!$income instanceof Income) {
                throw new \InvalidArgumentException("Income expected but got " . get_class($income));
            }
        }
    }
}
