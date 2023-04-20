<?php

namespace App\User\Infrastructure\Auth;

use App\User\Application\Auth\AuthTokenDecoderInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTDecoder implements AuthTokenDecoderInterface
{
    public function __construct(private readonly string $key)
    {
    }

    public function __invoke(string $token): array
    {
        return (array) JWT::decode($token, new Key($this->key, 'HS256'));
    }
}
