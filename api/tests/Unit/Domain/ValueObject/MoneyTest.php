<?php

namespace Test\Unit\Domain\ValueObject;

use App\Domain\ValueObject\Money;
use PHPUnit\Framework\TestCase;

class MoneyTest extends TestCase
{
    public function test_prints_to_string_with_2_decimals(): void
    {
        $object = new Money(1025);
        
        $string = (string) $object;
        
        self::assertEquals('10.25 €', $string);
    }
    
    public function test_prints_two_decimals_when_no_decimal_value(): void
    {
        $object = new Money(1000);
        
        $string = (string) $object;
        
        self::assertEquals('10.00 €', $string);
    }
}