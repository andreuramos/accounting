<?php

namespace App\Business\Domain\ValueObject;

class Address
{
    public function __construct(
        public readonly string $street,
        public readonly string $zip
    ) {
    }
}
