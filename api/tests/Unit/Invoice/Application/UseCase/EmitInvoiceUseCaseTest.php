<?php

namespace Test\Unit\Invoice\Application\UseCase;

use App\Invoice\Application\Command\EmitInvoiceCommand;
use App\Invoice\Application\UseCase\EmitInvoiceUseCase;
use App\Shared\Domain\ValueObject\Id;
use App\Transaction\Domain\Entity\Income;
use App\Transaction\Domain\Exception\IncomeNotFoundException;
use App\Transaction\Domain\Model\IncomeRepositoryInterface;
use App\Transaction\Domain\ValueObject\Money;
use App\User\Domain\Entity\User;
use App\User\Domain\ValueObject\Email;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class EmitInvoiceUseCaseTest extends TestCase
{
    use ProphecyTrait;

    private $incomeRepository;
    private User $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->incomeRepository = $this->prophesize(IncomeRepositoryInterface::class);
        $this->user = new User(new Id(1), new Email('a@b.com'), "");
    }

    public function test_fails_when_income_not_found()
    {
        $incomeId = new Id(123);
        $command = new EmitInvoiceCommand(
            $this->user,
            $incomeId,
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
        );
        $this->incomeRepository->getByIdOrFail($incomeId)
            ->willReturn($income);

        $this->expectException(IncomeNotFoundException::class);

        $useCase($command);
    }

    public function test_creates_business_for_customer()
    {
$this->markTestIncomplete();
    }

    private function buildUseCase(): EmitInvoiceUseCase
    {
        return new EmitInvoiceUseCase(
            $this->incomeRepository->reveal()
        );
    }
}
