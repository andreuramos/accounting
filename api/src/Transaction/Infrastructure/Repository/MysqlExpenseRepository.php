<?php

namespace App\Transaction\Infrastructure\Repository;

use App\Transaction\Domain\Entity\Expense;
use App\Transaction\Domain\Model\ExpenseRepositoryInterface;

class MysqlExpenseRepository implements ExpenseRepositoryInterface
{

    public function save(Expense $expense): void
    {
        // TODO: Implement save() method.
    }
}
