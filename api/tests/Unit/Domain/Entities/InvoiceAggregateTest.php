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
    public function test_no_lines_fails(): void
    {
        $invoice = new Invoice(
            new Id(null),
            new InvoiceNumber("whatever01"),
            new Id(23),
            new Id(44),
            new \DateTime()
        );
        
        $this->expectException(InvalidArgumentException::class);
        new InvoiceAggregate($invoice, []);
    }
    
    
}