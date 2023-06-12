<?php

namespace Test\Unit\Invoice\Application\UseCase;

use App\Business\Domain\Entity\Business;
use App\Business\Domain\Exception\BusinessNotFoundException;
use App\Business\Domain\Model\BusinessRepositoryInterface;
use App\Business\Domain\Model\TaxDataAggregateRepositoryInterface;
use App\Business\Domain\ValueObject\Address;
use App\Invoice\Application\Command\EmitInvoiceCommand;
use App\Invoice\Application\UseCase\EmitInvoiceUseCase;
use App\Invoice\Domain\Entity\Invoice;
use App\Invoice\Domain\Model\InvoiceRepositoryInterface;
use App\Invoice\Domain\Service\InvoiceNumberGenerator;
use App\Invoice\Domain\ValueObject\InvoiceNumber;
use App\Shared\Domain\ValueObject\Id;
use App\Transaction\Domain\Entity\Income;
use App\Transaction\Domain\Exception\IncomeNotFoundException;
use App\Transaction\Domain\Model\IncomeRepositoryInterface;
use App\Transaction\Domain\ValueObject\Money;
use App\User\Domain\Entity\User;
use App\User\Domain\ValueObject\Email;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;

class EmitInvoiceUseCaseTest extends TestCase
{
    use ProphecyTrait;

    private $incomeRepository;
    private $businessRepository;
    private $taxDataAggregateRepository;
    private $invoiceRepository;
    private $invoiceNumberGenerator;
    private User $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->incomeRepository = $this->prophesize(IncomeRepositoryInterface::class);
        $this->businessRepository = $this->prophesize(BusinessRepositoryInterface::class);
        $this->taxDataAggregateRepository = $this->prophesize(TaxDataAggregateRepositoryInterface::class);
        $this->invoiceRepository = $this->prophesize(InvoiceRepositoryInterface::class);
        $this->invoiceNumberGenerator = $this->prophesize(InvoiceNumberGenerator::class);
        $this->user = new User(new Id(1), new Email('a@b.com'), "");
    }

    public function test_fails_when_income_not_found()
    {
        $incomeId = new Id(123);
        $command = new EmitInvoiceCommand(
            $this->user,
            $incomeId,
            "My Business",
            "My Business SL",
            "TAX123",
            "Fake st 123",
            "07013",
        );
        $this->incomeRepository->getByIdOrFail($incomeId)
            ->shouldBeCalled()
            ->willThrow(IncomeNotFoundException::class);
        $useCase = $this->buildUseCase();

        $this->expectException(IncomeNotFoundException::class);

        $useCase($command);
    }

    public function test_fails_when_found_income_does_not_match_with_logged_user()
    {
        $useCase = $this->buildUseCase();
        $incomeId = new Id(123);
        $income = new Income($incomeId, new Id(2), new Money(100), "", new \DateTime());
        $command = new EmitInvoiceCommand(
            $this->user,
            $incomeId,
            "My Business",
            "My Business SL",
            "TAX123",
            "Fake St 123",
            "07041",
        );
        $this->incomeRepository->getByIdOrFail($incomeId)
            ->willReturn($income);

        $this->expectException(IncomeNotFoundException::class);

        $useCase($command);
    }

    public function test_fails_if_user_has_no_tax_data()
    {
        $useCase = $this->buildUseCase();
        $incomeId = new Id(123);
        $income = new Income($incomeId, $this->user->id(), new Money(100), "", new \DateTime());
        $taxNumber = "B071892093";
        $command = new EmitInvoiceCommand(
            $this->user,
            $incomeId,
            "My Business",
            "My Business SL",
            $taxNumber,
            "Fake st 123",
            "07001",
        );
        $receiverBusiness = new Business(
            new Id(1),
            "Receiver Company",
            ... $this->generateTaxData(),
        );
        $this->incomeRepository->getByIdOrFail($incomeId)
            ->willReturn($income);
        $this->businessRepository->getByTaxNumber($taxNumber)
            ->willReturn($receiverBusiness);
        $this->businessRepository->getByUserIdOrFail($this->user->id())
            ->willThrow(BusinessNotFoundException::class);

        $this->expectException(BusinessNotFoundException::class);

        $useCase($command);
    }

    public function test_creates_business_for_customer()
    {
        $useCase = $this->buildUseCase();
        $incomeId = new Id(123);
        $income = new Income($incomeId, $this->user->id(), new Money(100), "", new \DateTime());
        $taxNumber = "B071892093";
        $command = new EmitInvoiceCommand(
            $this->user,
            $incomeId,
            "My Business",
            "My Business SL",
            $taxNumber,
            "Fake st 123",
            "07001",
        );
        $this->incomeRepository->getByIdOrFail($incomeId)
            ->willReturn($income);
        $this->businessRepository->getByTaxNumber($taxNumber)
            ->willReturn(null);
        $this->businessRepository->getByUserIdOrFail($this->user->id())
            ->willReturn(new Business(
                new Id(1), "company", ...$this->generateTaxData()
            ));
        $this->invoiceNumberGenerator->__invoke(Argument::any())
            ->willReturn(new InvoiceNumber('123'));

        $this->businessRepository->save(Argument::type(Business::class))
            ->shouldBeCalled();

        $useCase($command);
    }

    public function test_creates_invoice_with_business_id()
    {
        $useCase = $this->buildUseCase();
        $incomeId = new Id(123);
        $income = new Income($incomeId, $this->user->id(), new Money(100), "", new \DateTime());
        $taxNumber = "B071892093";
        $command = new EmitInvoiceCommand(
            $this->user,
            $incomeId,
            "My Business",
            "My Business SL",
            $taxNumber,
            "Fake st 123",
            "07001",
        );
        $this->incomeRepository->getByIdOrFail($incomeId)
            ->willReturn($income);
        $receiverBusiness = new Business(
            new Id(1),
            "Receiver Company",
            ...$this->generateTaxData(),
        );
        $userBusiness = new Business(
            new Id(2),
            "Emitter Company",
            ...$this->generateTaxData(),
        );
        $invoiceNumber = new InvoiceNumber('20230001');
        $this->businessRepository->getByTaxNumber($taxNumber)
            ->willReturn($receiverBusiness);
        $this->businessRepository->save(Argument::type(Business::class))
            ->shouldNotBeCalled();
        $this->businessRepository->getByUserIdOrFail($this->user->id())
            ->willReturn($userBusiness);
        $this->invoiceNumberGenerator->__invoke($userBusiness)
            ->willReturn($invoiceNumber);

        $this->invoiceRepository->save(Argument::type(Invoice::class))
            ->shouldBeCalled();

        $response = $useCase($command);

        $this->assertEquals($invoiceNumber, $response);
    }

    private function buildUseCase(): EmitInvoiceUseCase
    {
        return new EmitInvoiceUseCase(
            $this->incomeRepository->reveal(),
            $this->businessRepository->reveal(),
            $this->invoiceRepository->reveal(),
            $this->invoiceNumberGenerator->reveal(),
        );
    }

    private function generateTaxData(): array
    {
        return [
            "brand " . random_int(0, 100),
            "B" . random_int(1000000,9999999),
            new Address("Fake street", "07013")
        ];
    }
}
