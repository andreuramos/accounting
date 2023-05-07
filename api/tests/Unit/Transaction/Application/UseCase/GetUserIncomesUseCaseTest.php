<?php

namespace Test\Unit\Transaction\Application\UseCase;

use App\Shared\Domain\ValueObject\Id;
use App\Transaction\Application\Command\GetUserIncomesCommand;
use App\Transaction\Application\Result\UserExpenses;
use App\Transaction\Application\Result\UserIncomes;
use App\Transaction\Application\UseCase\GetUserIncomesUseCase;
use App\Transaction\Domain\Entity\Income;
use App\Transaction\Domain\Model\IncomeRepositoryInterface;
use App\Transaction\Domain\ValueObject\Money;
use App\User\Domain\Entity\User;
use App\User\Domain\ValueObject\Email;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class GetUserIncomesUseCaseTest extends TestCase
{
    use ProphecyTrait;
    private $incomeRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->incomeRepository = $this->prophesize(IncomeRepositoryInterface::class);
    }

    public function test_no_incomes()
    {
        $user = new User(new Id(1), new Email("noincomes@usecase.test"), "");
        $this->incomeRepository->getByUser($user)
            ->shouldBeCalled()
            ->willReturn([]);
        $useCase = $this->getUseCase();
        $command = new GetUserIncomesCommand($user);

        $result = $useCase($command);

        $this->assertCount(0, $result->incomes);
    }

    public function test_returns_many_incomes()
    {
        $user = new User(new Id(1), new Email("manyincomes@usecase.test"), "");
        $income1 = new Income(
            new Id(123),
            $user->id(),
            new Money(100, "EUR"),
            "income 1",
            new \DateTime()
        );
        $income2 = new Income(
            new Id(124),
            $user->id(),
            new Money(200, "EUR"),
            "income 2",
            new \DateTime()
        );
        $this->incomeRepository->getByUser($user)
            ->shouldBeCalled()
            ->willReturn([$income1, $income2]);
        $useCase = $this->getUseCase();
        $command = new GetUserIncomesCommand($user);

        $result = $useCase($command);

        $this->assertCount(2, $result->incomes);
    }

    private function getUseCase(): GetUserIncomesUseCase
    {
        return new GetUserIncomesUseCase($this->incomeRepository->reveal());
    }
}
