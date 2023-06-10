<?php

namespace App\User\Infrastructure\Repository;

use App\Shared\Domain\ValueObject\Id;
use App\User\Domain\Entity\Account;
use App\User\Domain\Model\AccountRepositoryInterface;
use App\User\Domain\ValueObject\Email;

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

    public function getByOwnerEmail(Email $email): Account
    {
        return new Account(
            new Id(null),
            new Id(null),
        );
    }
}
