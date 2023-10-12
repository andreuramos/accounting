<?php

namespace App\Domain\Repository;

use App\Domain\Entities\Income;
use App\Domain\ValueObject\Id;

interface IncomeRepositoryInterface
{
    public function save(Income $income): Id;
    public function getByAccountId(Id $accountId): array;
    public function getByIdOrFail(Id $id): Income;
}
