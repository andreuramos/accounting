<?php

namespace App\Infrastructure\Auth;

use App\Application\Auth\AuthTokenDecoderInterface;
use App\Domain\Exception\InvalidAuthToken;
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
