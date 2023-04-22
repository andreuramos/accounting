<?php

namespace App\User\Infrastructure\Auth;

use App\User\Application\Auth\AuthTokenDecoderInterface;
use App\User\Domain\Exception\InvalidAuthToken;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTDecoder implements AuthTokenDecoderInterface
{
    public function __construct(private readonly string $key)
    {
    }

    public function __invoke(string $token): array
    {
        try {
            return (array) JWT::decode($token, new Key($this->key, 'HS256'));
        } catch (\Throwable $exception) {
            throw new InvalidAuthToken();
        }
    }
}
