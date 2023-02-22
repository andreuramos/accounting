<?php

namespace App\User\Infrastructure\Repository;

use App\User\Domain\Entity\User;
use App\User\Domain\Model\UserRepositoryInterface;
use PDO;

class MysqlUserRepository implements UserRepositoryInterface
{
    public function __construct(private readonly PDO $PDO)
    {
    }

    public function save(User $user): int
    {
        $email = $user->email()->toString();

        $stmt = $this->PDO->prepare("INSERT INTO user (email) VALUES (:email)");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        return 0;
    }
}
