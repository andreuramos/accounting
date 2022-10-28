<?php

namespace App\User\Application\Command;

class RegisterUserCommand
{
    public function __construct(private readonly string $email)
    {
    }

    public function email(): string
    {
        return $this->email;
    }
}
