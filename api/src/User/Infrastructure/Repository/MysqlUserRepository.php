<?php

namespace App\User\Infrastructure\Repository;

use App\User\Domain\Entity\User;
use App\User\Domain\Model\UserRepositoryInterface;

class MysqlUserRepository implements UserRepositoryInterface
{
    public function save(User $user): int
    {
        return 0;
    }
}
