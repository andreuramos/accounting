<?php

namespace App\Transaction\Application\Result;

use App\Transaction\Domain\Entity\Expense;

class UserExpenses
{
    public function __construct(public readonly array $expenses)
    {
        foreach ($expenses as $expense) {
            if (! $expense instanceof Expense) {
                throw new \InvalidArgumentException();
            }
        }
    }

    public function toArray(): array
    {
        $array = [];

        foreach($this->expenses as $expense) {
            $array[] = $expense->toArray();
        }

        return $array;
    }
}