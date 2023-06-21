<?php

namespace App\User\Infrastructure\Repository;

use App\Shared\Domain\ValueObject\Id;
use App\User\Domain\Entity\User;
use App\User\Domain\Exception\UserNotFoundException;
use App\User\Domain\Model\UserRepositoryInterface;
use App\User\Domain\ValueObject\AuthToken;
use App\User\Domain\ValueObject\Email;
use PDO;

class MysqlUserRepository implements UserRepositoryInterface
{
    public function __construct(private readonly PDO $PDO)
    {
    }

    public function save(User $user): void
    {
        $email = $user->email()->toString();
        $password = $user->passwordHash();
        $refreshToken = (string) $user->refreshToken();
        $accountId = $this->getAccountId($user);

        if (null === $user->id()->getInt()) {
            $stmt = $this->PDO->prepare(
                "INSERT INTO user (email, password, refresh_token, account_id) " .
                "VALUES (:email, :password, :refresh_token, :account_id)"
            );
        } else {
            $userId = $user->id()->getInt();
            $stmt = $this->PDO->prepare(
                "UPDATE user SET " .
                "email = :email, password = :password, refresh_token = :refresh_token, account_id = :account_id " .
                "WHERE id = :id"
            );
            $stmt->bindParam(':id', $userId);
        }
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':refresh_token', $refreshToken);
        $stmt->bindParam(':account_id', $accountId);

        $stmt->execute();
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

        $user = new User(
            new Id($result['id']),
            new Email($result['email']),
            $result['password']
        );
        $token = new AuthToken($result['refresh_token']);
        $user->setRefreshToken($token);
        $user->setAccountId(new Id($result['account_id']));

        return $user;
    }

    public function getByEmailOrFail(Email $email): User
    {
        $user = $this->getByEmail($email);

        if (null === $user) {
            throw new UserNotFoundException('email', $email->toString());
        }

        return $user;
    }

    public function linkBusinessToUser(Id $userId, string $taxNumber): void
    {
        $businessStmt = $this->PDO->prepare(
            'SELECT id FROM business WHERE tax_id = :taxNumber'
        );
        $businessStmt->bindParam('taxNumber', $taxNumber);
        $businessStmt->execute();
        $business = $businessStmt->fetch();

        $stmt = $this->PDO->prepare(
            'UPDATE user SET business_id = :businessId ' .
            'WHERE id = :userID'
        );
        $userIdInt = $userId->getInt();
        $stmt->bindParam('userID', $userIdInt);
        $stmt->bindParam('businessId', $business['id']);
        $stmt->execute();
    }

    private function getAccountId(User $user): int|null
    {
        return $user->accountId()?->getInt();
    }
}
