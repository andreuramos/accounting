<?php

namespace App\Domain\ValueObject;

use App\Domain\Exception\InvalidDeclarationPeriodException;

class DeclarationPeriod
{
    private function __construct(private string $periodName)
    {
    }

    public static function QUARTER(int $quarter): self
    {
        self::guardQuarter($quarter);
        return new self($quarter . 'T');
    }

    public function __toString(): string
    {
        return $this->periodName;
    }

    private static function guardQuarter(int $quarter)
    {
        if ($quarter < 1 || $quarter > 4) {
            throw new InvalidDeclarationPeriodException("quarter: " . $quarter);
        }
    }
}
