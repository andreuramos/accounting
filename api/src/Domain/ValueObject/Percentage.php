<?php

namespace App\Domain\ValueObject;

use App\Domain\Exception\InvalidArgumentException;

class Percentage
{
    public function __construct(public readonly float $value) {
        $this->guardBetween0And100($value);
    }

    private function guardBetween0And100(float $value)
    {
        if ($value < 0 || $value > 100.0) {
            throw new InvalidArgumentException('value', 'Percentage must be between 0 and 100');
        }
    }
}