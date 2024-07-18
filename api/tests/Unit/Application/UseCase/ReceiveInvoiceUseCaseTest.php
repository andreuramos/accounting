<?php

namespace Test\Unit\Application\UseCase;

use App\Application\UseCase\ReceiveInvoice\ReceiveInvoiceCommand;
use App\Application\UseCase\ReceiveInvoice\ReceiveInvoiceUseCase;
use App\Domain\Entities\Business;
use App\Domain\Entities\Invoice;
use App\Domain\Entities\InvoiceLine;
use App\Domain\Entities\User;
use App\Domain\Exception\BusinessNotFoundException;
use App\Domain\Exception\InvoiceAlreadyExistsException;
use App\Domain\Repository\BusinessRepositoryInterface;
use App\Domain\Repository\InvoiceLineRepositoryInterface;
use App\Domain\Repository\InvoiceRepositoryInterface;
use App\Domain\ValueObject\Address;
use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\Id;
use App\Domain\ValueObject\InvoiceNumber;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;

class ReceiveInvoiceUseCaseTest extends TestCase
{
    use ProphecyTrait;

    private $businessRepository;
    private $invoiceRepository;
    private $invoiceLineRepository;
    private User $user;
    
    public function setUp(): void
    {
        $this->businessRepository = $this->prophesize(BusinessRepositoryInterface::class);
        $this->invoiceRepository = $this->prophesize(InvoiceRepositoryInterface::class);
        $this->invoiceLineRepository = $this->prophesize(InvoiceLineRepositoryInterface::class);
        $this->user = new User(new Id(1), new Email('a@b.com'), "");
        $this->user->setAccountId(new Id(2));
    }

    public function test_fails_if_invoice_already_exists(): void
    {
        $command = new ReceiveInvoiceCommand(
            $this->user,
            "Brewery",
            "Brewery SK",
            "B076546546",
            "Camp Llarg 20",
            "07130",
            "20230000232",
            "",
            "2024-01-06",
            3000_00,
            600_00,
        );
        $this->invoiceRepository
            ->findByEmitterTaxNumberAndInvoiceNumber("B076546546", new InvoiceNumber("20230000232"))
            ->shouldBeCalled()
            ->willReturn($this->createMock(Invoice::class));
        $useCase = $this->buildUseCase();
        
        $this->expectException(InvoiceAlreadyExistsException::class);
        
        $useCase($command);
    }
    
    public function test_fails_if_user_has_no_tax_data(): void
    {
        $command = new ReceiveInvoiceCommand(
            $this->user,
            "Brewery",
            "Brewery SK",
            "B076546546",
            "Camp Llarg 20",
            "07130",
            "20230000232",
            "",
            "2024-01-06",
            3000_00,
            600_00,
        );
        $useCase = $this->buildUseCase();
        $this->businessRepository
            ->getByUserIdOrFail($this->user->id())
            ->shouldBeCalled()
            ->willThrow(new BusinessNotFoundException());
        
        $this->expectException(BusinessNotFoundException::class);
        
        $useCase($command);
    }
    
    public function test_creates_invoice_and_expense(): void
    {
        $command = new ReceiveInvoiceCommand(
            $this->user,
            "Brewery",
            "Brewery SK",
            "B076546546",
            "Camp Llarg 20",
            "07130",
            "20230000232",
            "",
            "2024-01-06",
            3000_00,
            600_00,
        );
        $this->invoiceRepository
            ->findByEmitterTaxNumberAndInvoiceNumber("B076546546", new InvoiceNumber("20230000232"))
            ->willReturn(null);
        $receiver = $this->buildBusiness("43186322G");
        $this->businessRepository
            ->getByUserIdOrFail($this->user->id())
            ->willReturn($receiver);
        $emitter = $this->buildBusiness("B076546546");
        $this->businessRepository
            ->getByTaxNumber("B076546546")
            ->willReturn($emitter);
        $useCase = $this->buildUseCase();
        $this->invoiceRepository
            ->save(Argument::type(Invoice::class))
            ->shouldBeCalled()
            ->willReturn(new Id(23));
        $this->invoiceLineRepository
            ->save(Argument::type(InvoiceLine::class))
            ->shouldBeCalled();

        $useCase($command);
    }
    
    public function test_creates_emiter_business_if_it_does_not_exists(): void
    {
        $this->markTestIncomplete();
        $this->businessRepository
            ->save(Argument::type(Business::class))
            ->shouldBeCalled();
    }

    private function buildUseCase(): ReceiveInvoiceUseCase
    {
        return new ReceiveInvoiceUseCase(
            $this->invoiceRepository->reveal(),
            $this->businessRepository->reveal(),
            $this->invoiceLineRepository->reveal(),
        );
    }

    private function buildBusiness(string $string): Business
    {
        return new Business(
            new Id(1),
            "Business",
            $string,
            $string,
            new Address("Street", "07001")
        );
    }
}