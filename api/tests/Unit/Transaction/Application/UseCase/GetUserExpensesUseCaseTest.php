<?php

namespace Test\Unit\Transaction\Application\UseCase;

use App\Shared\Domain\ValueObject\Id;
use App\Transaction\Application\Command\GetUserExpensesCommand;
use App\Transaction\Application\UseCase\GetUserExpensesUseCase;
use App\Transaction\Domain\Entity\Expense;
use App\Transaction\Domain\Model\ExpenseRepositoryInterface;
use App\Transaction\Domain\ValueObject\Money;
use App\User\Domain\Entity\User;
use App\User\Domain\ValueObject\Email;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class GetUserExpensesUseCaseTest extends TestCase
{
    use ProphecyTrait;

    private $expenseRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->expenseRepository = $this->prophesize(ExpenseRepositoryInterface::class);
    }

    public function test_no_expenses()
    {
        $user = new User(new Id(1), new Email("getexpenses@usecase.app"), "");
        $this->expenseRepository->getByUser($user)->willReturn([]);
        $useCase = new GetUserExpensesUseCase(
            $this->expenseRepository->reveal()
        );
        $command = new GetUserExpensesCommand($user);

        $result = $useCase($command);

        $this->assertCount(0, $result->expenses);
    }

    public function test_usecase_returns_same_expenses_than_repo()
    {
        $user = new User(new Id(1), new Email("getexpenses@usecase.app"), "");
        $expense1 = new Expense(
            new Id(1),
            new Id(1),
            new Money(200, "EUR"),
            "expense 1",
            new \DateTime()
        );
        $expense2 = new Expense(
            new Id(2),
            new Id(1),
            new Money(100, "EUR"),
            "expense 2",
            new \DateTime()
        );
        $this->expenseRepository->getByUser($user)
            ->shouldBeCalled()
            ->willReturn([
                $expense1, $expense2
            ]);
        $useCase = new GetUserExpensesUseCase(
            $this->expenseRepository->reveal()
        );
        $command = new GetUserExpensesCommand($user);

        $result = $useCase($command);

        $this->assertCount(2, $result->expenses);
    }
}
