<?php

namespace App\Transaction\Application\Result;

use App\Transaction\Domain\Entity\Expense;

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
