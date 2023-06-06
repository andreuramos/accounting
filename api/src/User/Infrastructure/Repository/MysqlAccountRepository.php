<?php

namespace App\User\Infrastructure\Repository;

use App\User\Domain\Entity\Account;
use App\User\Domain\Model\AccountRepositoryInterface;

class MysqlAccountRepository implements AccountRepositoryInterface
{
    public function save(Account $account): void
    {
        // TODO: Implement save() method.
    }
}
