<?php

namespace App\Transaction\Domain\Model;

use App\Transaction\Domain\Entity\Income;

interface IncomeRepositoryInterface
{
    public function save(Income $income): void;
}
