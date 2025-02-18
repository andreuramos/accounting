<?php

namespace Test\Unit\Domain\Service;

use App\Application\Service\Timestamper;
use App\Application\UseCase\EmitInvoice\InvoiceNumberGenerator;
use App\Domain\Entities\Business;
use App\Domain\Entities\Invoice;
use App\Domain\Repository\InvoiceAggregateRepositoryInterface;
use App\Domain\ValueObject\Address;
use App\Domain\ValueObject\Id;
use App\Domain\ValueObject\InvoiceNumber;
use DateTime;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;

class InvoiceNumberGeneratorTest extends TestCase
{
    use ProphecyTrait;

    private $invoiceAggregateRepository;
    private $timestamper;
    private Business $business;

    public function setUp(): void
    {
        parent::setUp();
        $this->invoiceAggregateRepository = $this->prophesize(InvoiceAggregateRepositoryInterface::class);
        $this->timestamper = $this->prophesize(Timestamper::class);
        $this->business = new Business(
            new Id(1), "mybusiness", "-", "", new Address("", "")
        );
    }

    public function test_starts_with_year_prefix()
    {
        $this->invoiceAggregateRepository->findLastEmittedByBusiness(Argument::type(Business::class))
            ->willReturn(null);
        $this->timestamper->__invoke()->willReturn(date_create('2023-05-23'));
        $service = $this->buildService();

        $result = $service($this->business);

        $this->assertStringStartsWith('2023', $result->number);
    }

    public function test_suffix_is_5_characters_long()
    {
        $this->invoiceAggregateRepository->findLastEmittedByBusiness(Argument::type(Business::class))
            ->willReturn(null);
        $this->timestamper->__invoke()->willReturn(date_create('2023-05-23'));
        $service = $this->buildService();

        $result = $service($this->business);

        $number = $result->number;
        $suffix = substr($number, 5);
        $this->assertEquals(5, strlen($suffix));
    }

    public function test_generates_correlative_to_the_last_one()
    {
        $lastInvoice = new Invoice(
            new Id(1),
            new InvoiceNumber('2023-00001'),
            $this->business->id,
            new Id(23),
            new \DateTime('2023-05-24'),
        );
        $this->invoiceAggregateRepository->findLastEmittedByBusiness(Argument::type(Business::class))
            ->willReturn($lastInvoice);
        $this->timestamper->__invoke()->willReturn(date_create('2023-05-24'));
        $service = $this->buildService();

        $result = $service($this->business);

        $correlativeValue = (int) substr($result->number, 5);
        $this->assertEquals(2, $correlativeValue);
    }
    
    public function test_starts_over_numeration_if_last_invoice_is_in_previous_year(): void
    {
        $invoiceDate = (new \DateTime())->sub(new \DateInterval('P1Y'));
        $lastInvoice = new Invoice(
            new Id(1),
            new InvoiceNumber('2023-00005'),
            $this->business->id,
            new Id(23),
            $invoiceDate,
        );
        $this->invoiceAggregateRepository->findLastEmittedByBusiness(Argument::type(Business::class))
            ->willReturn($lastInvoice);
        $this->timestamper->__invoke()->willReturn(new \DateTime());
        $service = $this->buildService();

        $result = $service($this->business);

        $correlativeValue = (int) substr($result->number, 5);
        $this->assertEquals(1, $correlativeValue);
    }

    private function buildService(): InvoiceNumberGenerator
    {
        return new InvoiceNumberGenerator(
            $this->invoiceAggregateRepository->reveal(),
            $this->timestamper->reveal(),
        );
    }
}
