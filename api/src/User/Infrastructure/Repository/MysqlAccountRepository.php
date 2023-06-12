<?php

namespace App\User\Infrastructure\Repository;

use App\Shared\Domain\ValueObject\Id;
use App\User\Domain\Entity\Account;
use App\User\Domain\Exception\AccountNotFoundException;
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

    public function getByOwnerEmailOrFail(Email $email): Account
    {
        $stmt = $this->PDO->prepare(
            'SELECT a.id as account_id, u.id as user_id FROM account a ' .
            'LEFT JOIN user u ON a.main_user_id = u.id ' .
            'WHERE u.email = :email'
        );
        $stringEmail = $email->toString();
        $stmt->bindParam(':email', $stringEmail);

        $stmt->execute();
        $result = $stmt->fetch();

        if (false === $result) {
            throw new AccountNotFoundException($stringEmail);
        }

        return new Account(
            new Id($result['account_id']),
            new Id($result['user_id']),
        );
    }
}
