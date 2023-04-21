<?php

namespace App\User\Domain\Entity;

use App\Shared\Domain\ValueObject\Id;
use App\User\Application\Auth\AuthTokenInterface;
use App\User\Domain\ValueObject\Email;

class User
{
    private ?AuthTokenInterface $refreshToken = null;

    public function __construct(
        private readonly Id $id,
        private readonly Email $email,
        private readonly string $passwordHash
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

    public function setRefreshToken(AuthTokenInterface $token)
    {
        $this->refreshToken = $token;
    }

    public function refreshToken(): ?AuthTokenInterface
    {
        return $this->refreshToken;
    }

    public function toExposableArray(): array
    {
        return [
            'email' => $this->email->toString()
        ];
    }
}
