<?php

namespace App\Domain\ValueObject;

class Address
{
    public function __construct(
        public readonly string $street,
        public readonly string $zip
    ) {
    }
}
