<?php

namespace App\Domain\ValueObject;

class AuthToken
{
    public function __construct(
        public readonly string $value
    ) {
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
