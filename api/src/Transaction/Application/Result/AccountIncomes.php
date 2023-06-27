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

    public function toArray(): array
    {
        return array_map(function (Income $income) {
            return [
                'id' => $income->id->getInt(),
                'user_id' => $income->userId->getInt(),
                'amount_cents' => $income->amount->amountCents,
                'currency' => $income->amount->currency,
                'description' => $income->description,
                'date' => $income->date->format('Y-m-d'),
            ];
        }, $this->incomes);
    }
}
