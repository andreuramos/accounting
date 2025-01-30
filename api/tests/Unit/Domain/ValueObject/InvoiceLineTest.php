<?php

namespace Test\Unit\Domain\ValueObject;

use App\Domain\ValueObject\InvoiceLine;
use App\Domain\ValueObject\Money;
use App\Domain\ValueObject\Percentage;
use PHPUnit\Framework\TestCase;

class InvoiceLineTest extends TestCase
{
    public function test_calculates_base_amount(): void
    {
        $invoiceLine = new InvoiceLine(
            "whatever",
            2,
            new Money(1000),
            new Percentage(21.00)
        );
        
        $baseAmount = $invoiceLine->baseAmount();
        
        self::assertInstanceOf(Money::class, $baseAmount);
        self::assertEquals(2000, $baseAmount->amountCents);
    }
    
    public function test_calculates_vat_amount(): void
    {
        $invoiceLine = new InvoiceLine(
            "product",
            2,
            new Money(1000),
            new Percentage(21.00),
        );
        
        $vat = $invoiceLine->vatAmount();
        
        self::assertInstanceOf(Money::class, $vat);
        self::assertEquals(420, $vat->amountCents);
    }
    
    public function test_calculates_line_total_amount(): void
    {
        $invoiceLine = new InvoiceLine(
            "product",
            2,
            new Money(1000),
            new Percentage(21.00),
        );
        
        $totalAmount = $invoiceLine->totalAmount();
        
        self::assertInstanceOf(Money::class, $totalAmount);
        self::assertEquals(2420, $totalAmount->amountCents);
    }
}