<?php

namespace App\UseCase\RefreshToken;

class RefreshTokensCommand
{
    public function __construct(public readonly string $refreshToken)
    {
    }
}
