<?php

namespace App\Domain\ValueObject;

class Address
{
    public function __construct(
        public readonly string $street,
        public readonly string $zip
    ) {
    }

    public function __toString(): string
    {
        return $this->street . ', ' . $this->zip;
    }
}
