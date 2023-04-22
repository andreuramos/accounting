<?php

namespace App\User\Application\Command;

class RefreshTokensCommand
{

    public function __construct(public readonly string $refreshToken)
    {
    }
}
