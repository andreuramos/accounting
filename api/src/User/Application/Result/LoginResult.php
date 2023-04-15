<?php

namespace App\User\Application\Result;

class LoginResult
{
    public function __construct(
        readonly string $token,
        readonly string $refresh
    ) {
    }
}
