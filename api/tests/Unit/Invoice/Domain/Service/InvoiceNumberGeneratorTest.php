<?php

namespace Test\Unit\Invoice\Domain\Service;

use App\Invoice\Domain\Entity\Business;
use App\Invoice\Domain\Model\InvoiceRepositoryInterface;
use App\Invoice\Domain\Service\InvoiceNumberGenerator;
use App\Shared\Application\Service\Timestamper;
use App\Shared\Domain\ValueObject\Id;
use App\Tax\Domain\Entity\TaxData;
use App\Tax\Domain\ValueObject\Address;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;

class InvoiceNumberGeneratorTest extends TestCase
{
    use ProphecyTrait;

    private $invoiceRepository;
    private $timestamper;

    public function setUp(): void
    {
        parent::setUp();
        $this->invoiceRepository = $this->prophesize(InvoiceRepositoryInterface::class);
        $this->timestamper = $this->prophesize(Timestamper::class);
    }

    public function test_adds_year_prefix()
    {
        $this->invoiceRepository->getLastEmittedByBusiness(Argument::type(Business::class))
            ->willReturn(null);
        $this->timestamper->__invoke()->willReturn(date_create('2023-05-23'));
        $business = new Business(
            new Id(1), "mybusiness", new TaxData(new Id(1), "-", "", new Address("", ""))
        );
        $service = $this->buildService();

        $result = $service($business);

        $this->assertStringContainsString('2023', $result->number);
    }

    private function buildService(): InvoiceNumberGenerator
    {
        return new InvoiceNumberGenerator(
            $this->invoiceRepository->reveal(),
            $this->timestamper->reveal(),
        );
    }
}
