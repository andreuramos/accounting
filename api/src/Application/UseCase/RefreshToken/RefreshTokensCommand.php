<?php

namespace App\Application\UseCase\RefreshToken;

class RefreshTokensCommand
{
    public function __construct(public readonly string $refreshToken)
    {
    }
}
