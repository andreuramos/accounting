<?php

namespace App\Transaction\Domain\Model;

use App\Transaction\Domain\Entity\Income;
use App\User\Domain\Entity\User;

interface IncomeRepositoryInterface
{
    public function save(Income $income): void;
    public function getByUser(User $user): array;
}
