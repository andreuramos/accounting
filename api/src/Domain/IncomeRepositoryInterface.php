<?php

namespace App\Domain;

interface IncomeRepositoryInterface
{
    public function save(Income $income): Id;
    public function getByAccountId(Id $accountId): array;
    public function getByIdOrFail(Id $id): Income;
}
