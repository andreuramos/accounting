<?php

namespace App\User\Infrastructure\Repository;

use App\User\Domain\Entity\Account;
use App\User\Domain\Model\AccountRepositoryInterface;

class MysqlAccountRepository implements AccountRepositoryInterface
{
    public function __construct(private readonly \PDO $PDO)
    {
    }

    public function save(Account $account): void
    {
        $stmt = $this->PDO->prepare(
            'INSERT INTO account (main_user_id) VALUES (:user_id)'
        );
        $userId = $account->mainUserId->getInt();
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
    }
}
