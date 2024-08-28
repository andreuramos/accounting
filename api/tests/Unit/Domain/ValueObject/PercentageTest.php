<?php

namespace Test\Unit\Domain\ValueObject;

use App\Domain\Exception\InvalidArgumentException;
use App\Domain\ValueObject\Percentage;
use PHPUnit\Framework\TestCase;

class PercentageTest extends TestCase
{
    public function test_valid_percentage_instances_object(): void
    {
        $object = new Percentage(1);
        
        self::assertInstanceOf(Percentage::class, $object);
    }
    
    public function test_negative_percentage_throws_exception(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Percentage(-1);
    }
    
    public function test_over_100_percentage_throws_error(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Percentage(100.1);
    }
}