<?php

namespace Test\Unit\Domain\Entities;

use App\Domain\Entities\Invoice;
use App\Domain\Entities\InvoiceAggregate;
use App\Domain\Entities\InvoiceLine;
use App\Domain\Exception\InvalidArgumentException;
use App\Domain\ValueObject\Id;
use App\Domain\ValueObject\InvoiceNumber;
use App\Domain\ValueObject\Money;
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
    
    public function test_total_amount_from_lines(): void
    {
        $invoiceLine = new InvoiceLine(
            new Id(42),
            "Capsa 12 Moixa Amber Ale",
            1,
            new Money(2664)
        );
        
        $aggregate = new InvoiceAggregate(
            $this->invoice,
            [$invoiceLine],
        );
        
        self::assertEquals(2664, $aggregate->totalAmount()->amountCents);
    }
}