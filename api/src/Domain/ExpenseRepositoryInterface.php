<?php

namespace App\Domain;

interface ExpenseRepositoryInterface
{
    public function save(Expense $expense): void;
    public function getByAccountId(Id $accountId): array;
}
