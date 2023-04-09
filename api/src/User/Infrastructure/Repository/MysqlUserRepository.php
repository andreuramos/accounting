<?php

namespace App\User\Infrastructure\Repository;

use App\User\Domain\Entity\User;
use App\User\Domain\Model\UserRepositoryInterface;
use App\User\Domain\ValueObject\Email;
use PDO;

class MysqlUserRepository implements UserRepositoryInterface
{
    public function __construct(private readonly PDO $PDO)
    {
    }

    public function save(User $user): int
    {
        $email = $user->email()->toString();
        $password = $user->passwordHash();

        $stmt = $this->PDO->prepare("INSERT INTO user (email, password) VALUES (:email, :password)");
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->execute();

        return 0;
    }

    public function getByEmail(Email $email): ?User
    {
        $emailStr = $email->toString();
        $stmt = $this->PDO->prepare(
            "SELECT user.* FROM user WHERE email = :email;"
        );
        $stmt->bindParam(':email', $emailStr);
        $stmt->execute();
        $result = $stmt->fetch();

        if (empty($result)) {
            return null;
        }

        return new User(new Email($result['email']));
    }
}
