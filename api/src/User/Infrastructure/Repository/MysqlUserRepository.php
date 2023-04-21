<?php

namespace App\User\Infrastructure\Repository;

use App\Shared\Domain\ValueObject\Id;
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
        $refreshToken = (string) $user->refreshToken();

        if (null === $user->id()->getInt()) {
            $stmt = $this->PDO->prepare("INSERT INTO user (email, password, refresh_token) VALUES (:email, :password, :refresh_token)");
        } else {
            $stmt = $this->PDO->prepare("UPDATE user SET email = :email, password = :password, refresh_token = :refresh_token WHERE id = :id");
            $stmt->bindParam(':id', $id);
        }
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':refresh_token', $refreshToken);
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

        return new User(
            new Id($result['id']),
            new Email($result['email']),
            $result['password']
        );
    }
}
