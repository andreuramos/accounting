<?php

namespace App\User\Domain\ValueObject;

class Email
{
    public function __construct(private readonly string $email)
    {
    }

    public function toString(): string
    {
        return $this->email;
    }
}
