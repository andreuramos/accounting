<?php

namespace App\User\Infrastructure\Auth;

use App\User\Application\Auth\AuthTokenInterface;

class JWTToken implements AuthTokenInterface
{

    public function __construct(private readonly string $value)
    {
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
