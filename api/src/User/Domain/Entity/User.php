<?php

namespace App\User\Domain\Entity;

use App\User\Application\Auth\AuthTokenInterface;
use App\User\Domain\ValueObject\Email;

class User
{
    private AuthTokenInterface $refreshToken;

    public function __construct(
        private readonly Email $email,
        private readonly string $passwordHash
    ) {
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
