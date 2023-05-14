<?php

namespace App\Transaction\Domain\Model;

use App\Shared\Domain\ValueObject\Id;
use App\Transaction\Domain\Entity\Income;
use App\User\Domain\Entity\User;

interface IncomeRepositoryInterface
{
    public function save(Income $income): Id;
    public function getByUser(User $user): array;
}
