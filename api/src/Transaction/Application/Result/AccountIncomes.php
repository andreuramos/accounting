<?php

namespace App\Transaction\Application\Result;

use App\Transaction\Domain\Entity\Income;

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
