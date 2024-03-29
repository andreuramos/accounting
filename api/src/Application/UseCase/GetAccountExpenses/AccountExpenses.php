<?php

namespace App\Application\UseCase\GetAccountExpenses;

use App\Domain\Entities\Expense;

class AccountExpenses
{
    public function __construct(public readonly array $expenses)
    {
        foreach ($expenses as $expense) {
            if (! $expense instanceof Expense) {
                throw new \InvalidArgumentException();
            }
        }
    }
}
