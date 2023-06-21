<?php

namespace App\Transaction\Domain\Model;

use App\Shared\Domain\ValueObject\Id;
use App\Transaction\Domain\Entity\Expense;
use App\User\Domain\Entity\User;

interface ExpenseRepositoryInterface
{
    public function save(Expense $expense): void;
    public function getByAccountId(Id $accountId): array;
}
