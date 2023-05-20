<?php

namespace Test\Unit\Invoice\Application\UseCase;

use App\Invoice\Application\Command\EmitInvoiceCommand;
use App\Invoice\Application\UseCase\EmitInvoiceUseCase;
use App\Invoice\Domain\Entity\Business;
use App\Invoice\Domain\Model\BusinessRepositoryInterface;
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
    private User $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->incomeRepository = $this->prophesize(IncomeRepositoryInterface::class);
        $this->businessRepository = $this->prophesize(BusinessRepositoryInterface::class);
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

        $this->businessRepository->save(Argument::type(Business::class))
            ->shouldBeCalled()
            ->willReturn(new Id(3));

        $useCase($command);
    }

    private function buildUseCase(): EmitInvoiceUseCase
    {
        return new EmitInvoiceUseCase(
            $this->incomeRepository->reveal(),
            $this->businessRepository->reveal(),
        );
    }
}
