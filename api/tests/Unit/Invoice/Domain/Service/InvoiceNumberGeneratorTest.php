<?php

namespace Test\Unit\Invoice\Domain\Service;

use App\Domain\Address;
use App\Domain\Business;
use App\Domain\Id;
use App\Domain\Invoice;
use App\Domain\InvoiceNumber;
use App\Domain\InvoiceRepositoryInterface;
use App\Service\Timestamper;
use App\UseCase\EmitInvoice\InvoiceNumberGenerator;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;

class InvoiceNumberGeneratorTest extends TestCase
{
    use ProphecyTrait;

    private $invoiceRepository;
    private $timestamper;
    private Business $business;

    public function setUp(): void
    {
        parent::setUp();
        $this->invoiceRepository = $this->prophesize(InvoiceRepositoryInterface::class);
        $this->timestamper = $this->prophesize(Timestamper::class);
        $this->business = new Business(
            new Id(1), "mybusiness", "-", "", new Address("", "")
        );
    }

    public function test_starts_with_year_prefix()
    {
        $this->invoiceRepository->getLastEmittedByBusiness(Argument::type(Business::class))
            ->willReturn(null);
        $this->timestamper->__invoke()->willReturn(date_create('2023-05-23'));
        $service = $this->buildService();

        $result = $service($this->business);

        $this->assertStringStartsWith('2023', $result->number);
    }

    public function test_suffix_is_8_characters_long()
    {
        $this->invoiceRepository->getLastEmittedByBusiness(Argument::type(Business::class))
            ->willReturn(null);
        $this->timestamper->__invoke()->willReturn(date_create('2023-05-23'));
        $service = $this->buildService();

        $result = $service($this->business);

        $number = $result->number;
        $suffix = substr($number, 4);
        $this->assertEquals(8, strlen($suffix));
    }

    public function test_generates_correlative_to_the_last_one()
    {
        $lastInvoice = new Invoice(
            new Id(1),
            new InvoiceNumber('202300000001'),
            new Id(1),
            $this->business->id,
            new Id(23),
            new \DateTime(),
            [],
        );
        $this->invoiceRepository->getLastEmittedByBusiness(Argument::type(Business::class))
            ->willReturn($lastInvoice);
        $this->timestamper->__invoke()->willReturn(date_create('2023-05-24'));
        $service = $this->buildService();

        $result = $service($this->business);

        $correlativeValue = (int) substr($result->number, 4);
        $this->assertEquals(2, $correlativeValue);
    }

    private function buildService(): InvoiceNumberGenerator
    {
        return new InvoiceNumberGenerator(
            $this->invoiceRepository->reveal(),
            $this->timestamper->reveal(),
        );
    }
}
