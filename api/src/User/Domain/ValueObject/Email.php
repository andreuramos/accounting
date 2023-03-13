<?php

namespace App\User\Domain\ValueObject;

use App\User\Domain\Exception\InvalidEmailException;

class Email
{
    public function __construct(
        private readonly string $email,
        string $validationRegex = "/.*/"
    ) {
        if (!preg_match($validationRegex, $email)) {
            throw new InvalidEmailException();
        }
    }

    public function toString(): string
    {
        return $this->email;
    }
}
