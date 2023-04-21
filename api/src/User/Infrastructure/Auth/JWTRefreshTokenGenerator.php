<?php

namespace App\User\Infrastructure\Auth;

use App\User\Application\Auth\AuthTokenInterface;
use App\User\Application\Auth\RefreshTokenGeneratorInterface;
use App\User\Domain\Entity\User;
use Firebase\JWT\JWT;

class JWTRefreshTokenGenerator implements RefreshTokenGeneratorInterface
{
    public function __construct(
        private readonly string $signatureKey,
        private readonly int $ttl
    ) {
    }

    public function __invoke(User $user): AuthTokenInterface
    {
        $expiration = (new \DateTime())->getTimestamp() + $this->ttl;
        $payload = [
            'email' => $user->email()->toString(),
            'expiration' => $expiration
        ];

        $encoded = JWT::encode($payload, $this->signatureKey, 'HS256');
        return new JWTToken($encoded);
    }
}
