<?php

namespace App\UseCase\Login;

use App\Domain\ValueObject\AuthToken;

class LoginResult
{
    public function __construct(
        readonly AuthToken $token,
        readonly AuthToken $refresh
    ) {
    }
}
