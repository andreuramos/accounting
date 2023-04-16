<?php

namespace App\User\Infrastructure\Auth;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTDecoder
{
    public function __construct(private readonly string $key)
    {
    }

    public function __invoke(string $jwt): array
    {
        return (array) JWT::decode($jwt, new Key($this->key, 'HS256'));
    }
}
