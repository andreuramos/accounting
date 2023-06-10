<?php

namespace App\User\Domain\Entity;

use App\Shared\Domain\ValueObject\Id;
use App\User\Domain\ValueObject\AuthToken;
use App\User\Domain\ValueObject\Email;

class User
{
    private ?AuthToken $refreshToken = null;
    private ?Id $accountId = null;

    public function __construct(
        private readonly Id $id,
        private readonly Email $email,
        private readonly string $passwordHash,
    ) {
    }

    public function id(): Id
    {
        return $this->id;
    }

    public function email(): Email
    {
        return $this->email;
    }

    public function passwordHash(): string
    {
        return $this->passwordHash;
    }

    public function setRefreshToken(AuthToken $token)
    {
        $this->refreshToken = $token;
    }

    public function refreshToken(): ?AuthToken
    {
        return $this->refreshToken;
    }

    public function setAccountId(Id $accountId): void
    {
        $this->accountId = $accountId;
    }

    public function toExposableArray(): array
    {
        return [
            'email' => $this->email->toString()
        ];
    }
}
