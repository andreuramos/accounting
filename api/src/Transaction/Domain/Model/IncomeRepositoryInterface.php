<?php

namespace App\Transaction\Domain\Model;

use App\Shared\Domain\ValueObject\Id;
use App\Transaction\Domain\Entity\Income;

interface IncomeRepositoryInterface
{
    public function save(Income $income): Id;
    public function getByAccountId(Id $accountId): array;
    public function getByIdOrFail(Id $id): Income;
}
