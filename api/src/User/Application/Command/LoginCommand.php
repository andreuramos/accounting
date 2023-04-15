<?php

namespace App\User\Application\Command;

use App\User\Domain\ValueObject\Email;

class LoginCommand
{
    public function __construct(
        readonly Email $email,
        readonly string $password
    ) {
    }
}
