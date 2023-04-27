<?php

namespace App\Transaction\Domain\Model;

use App\Transaction\Domain\Entity\Expense;

interface ExpenseRepositoryInterface
{
    public function save(Expense $expense): void;
}
