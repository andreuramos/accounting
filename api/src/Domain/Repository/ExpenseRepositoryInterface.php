<?php

namespace App\Domain\Repository;

use App\Domain\Entities\Expense;
use App\Domain\ValueObject\Id;

interface ExpenseRepositoryInterface
{
    public function save(Expense $expense): void;
    public function getByAccountId(Id $accountId): array;
}
