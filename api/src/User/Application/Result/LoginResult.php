<?php

namespace App\User\Application\Result;

use App\User\Domain\ValueObject\AuthToken;

class LoginResult
{
    public function __construct(
        readonly AuthToken $token,
        readonly AuthToken $refresh
    ) {
    }
}
