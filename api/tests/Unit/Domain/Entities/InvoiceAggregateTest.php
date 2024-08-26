<?php

namespace Test\Unit\Domain\Entities;

use App\Domain\Entities\Invoice;
use App\Domain\Entities\InvoiceAggregate;
use App\Domain\Exception\InvalidArgumentException;
use App\Domain\ValueObject\Id;
use App\Domain\ValueObject\InvoiceNumber;
use PHPUnit\Framework\TestCase;

class InvoiceAggregateTest extends TestCase
{
    public function setUp(): void
    {
        $this->invoice = new Invoice(
            new Id(null),
            new InvoiceNumber("whatever01"),
            new Id(23),
            new Id(44),
            new \DateTime()
        );
    }

    public function test_no_lines_fails(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new InvoiceAggregate($this->invoice, []);
    }

    public function test_wrong_class_lines_fails(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new InvoiceAggregate($this->invoice, [new \stdClass()]);
    }
}