<?php

namespace App\User\Domain\ValueObject;

use App\User\Domain\Exception\InvalidEmailException;

class Email
{
    public const VALIDATION_REGEX = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';

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
