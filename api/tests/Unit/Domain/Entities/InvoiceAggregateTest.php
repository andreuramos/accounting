<?php

namespace Test\Unit\Domain\Entities;

use App\Domain\Entities\Invoice;
use App\Domain\Entities\InvoiceAggregate;
use App\Domain\Exception\InvalidArgumentException;
use App\Domain\ValueObject\Id;
use App\Domain\ValueObject\InvoiceLine;
use App\Domain\ValueObject\InvoiceNumber;
use App\Domain\ValueObject\Money;
use App\Domain\ValueObject\Percentage;
use PHPUnit\Framework\TestCase;

class InvoiceAggregateTest extends TestCase
{
    const INVOICE_ID = 1256;
    const INVOICE_NUMBER = "whatever01";

    public function setUp(): void
    {
        $this->invoice = new Invoice(
            new Id(self::INVOICE_ID),
            new InvoiceNumber(self::INVOICE_NUMBER),
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

    public function test_proxies_invoice_entity_id(): void
    {
        $invoiceLine = new InvoiceLine(
            "Capsa 6 Moixa Feresta",
            1,
            new Money(1600),
            new Percentage(21),
        );
        $aggregate = new InvoiceAggregate($this->invoice, [$invoiceLine]);

        self::assertEquals(self::INVOICE_ID, $aggregate->id()->getInt());
    }

    public function test_proxies_invoice_number(): void
    {
        $invoiceLine = new InvoiceLine(
            "Capsa 6 Moixa Feresta",
            1,
            new Money(1600),
            new Percentage(21),
        );
        $aggregate = new InvoiceAggregate($this->invoice, [$invoiceLine]);
        
        self::assertEquals(self::INVOICE_NUMBER, $aggregate->invoiceNumber());
    }

    public function test_total_amount_from_lines(): void
    {
        $invoiceLine = new InvoiceLine(
            "Capsa 12 Moixa Amber Ale",
            1,
            new Money(2664),
            new Percentage(21),
        );

        $aggregate = new InvoiceAggregate(
            $this->invoice,
            [$invoiceLine],
        );

        self::assertEquals(2664, $aggregate->totalAmount()->amountCents);
    }
}