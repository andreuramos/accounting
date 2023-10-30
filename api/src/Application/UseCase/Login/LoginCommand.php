<?php

namespace App\Application\UseCase\Login;

use App\Domain\ValueObject\Email;

class LoginCommand
{
    public function __construct(
        readonly Email $email,
        readonly string $password
    ) {
    }
}
