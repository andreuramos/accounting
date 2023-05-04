<?php

namespace App\Transaction\Infrastructure\Repository;

use App\Transaction\Domain\Entity\Income;
use App\Transaction\Domain\Model\IncomeRepositoryInterface;

class MysqlIncomeRepository implements IncomeRepositoryInterface
{

    public function save(Income $income): void
    {
        // TODO: Implement save() method.
    }
}
