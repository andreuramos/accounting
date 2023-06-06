<?php

namespace App\User\Domain\Model;

use App\User\Domain\Entity\Account;

interface AccountRepositoryInterface
{
    public function save(Account $account): void;
}
