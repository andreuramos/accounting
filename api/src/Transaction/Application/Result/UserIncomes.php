<?php

namespace App\Transaction\Application\Result;

use App\Transaction\Domain\Entity\Income;

class UserIncomes
{
    public function __construct(
        public readonly array $incomes
    ) {
    }

    public function toArray()
    {
        return array_map(function (Income $income) {
            return $income->toArray();
        }, $this->incomes);
    }
}
