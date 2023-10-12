<?php

namespace App\Domain;

class Address
{
    public function __construct(
        public readonly string $street,
        public readonly string $zip
    ) {
    }
}
