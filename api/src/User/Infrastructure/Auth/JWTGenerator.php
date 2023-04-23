<?php

namespace App\User\Infrastructure\Auth;

use App\User\Application\Auth\AuthTokenGeneratorInterface;
use App\User\Domain\Entity\User;
use App\User\Domain\ValueObject\AuthToken;
use Firebase\JWT\JWT;

class JWTGenerator implements AuthTokenGeneratorInterface
{
    public function __construct(
        private readonly string $signatureKey,
        private readonly int $ttl
    ) {
    }

    public function __invoke(User $user): AuthToken
    {
        $expiration = (new \DateTime())->getTimestamp() + $this->ttl;
        $tokenPayload = [
            'user' => $user->email()->toString(),
            'expiration' => $expiration,
        ];

        $encoded = JWT::encode($tokenPayload, $this->signatureKey, 'HS256');
        return new AuthToken($encoded);
    }
}
