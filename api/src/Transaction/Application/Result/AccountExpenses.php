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

    public function toArray(): array
    {
        return array_map(function (Expense $expense) {
            return [
                'id' => $expense->id->getInt(),
                'account_id' => $expense->accountId->getInt(),
                'amount_cents' => $expense->amount->amountCents,
                'currency' => $expense->amount->currency,
                'description' => $expense->description,
                'date' => $expense->date->format('Y-m-d')
            ];
        }, $this->expenses);
    }
}
