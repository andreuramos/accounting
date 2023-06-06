<?php

namespace App\User\Infrastructure\Repository;

use App\User\Domain\Model\AccountRepositoryInterface;

class MysqlAccountRepository implements AccountRepositoryInterface
{
    public function createForUser(string $email): void
    {
        // TODO: Implement createForUser() method.
    }
}
