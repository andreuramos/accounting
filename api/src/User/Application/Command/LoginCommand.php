<?php

namespace App\User\Application\Command;

use App\User\Domain\ValueObject\Email;

class LoginCommand
{
    public function __construct(
        private readonly Email $email,
        private readonly string $password
    ) {

    }

    public function email(): Email
    {
        return $this->email;
    }

    public function password(): string
    {
        return $this->password;
    }
}
