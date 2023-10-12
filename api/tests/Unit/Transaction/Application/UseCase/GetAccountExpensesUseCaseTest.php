<?php

namespace Test\Unit\Transaction\Application\UseCase;

use App\Domain\Expense;
use App\Domain\ExpenseRepositoryInterface;
use App\Domain\Id;
use App\Domain\Money;
use App\UseCase\GetAccountExpenses\GetAccountExpensesCommand;
use App\UseCase\GetAccountExpenses\GetAccountExpensesUseCase;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class GetAccountExpensesUseCaseTest extends TestCase
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
        $accountId = new Id(2);
        $this->expenseRepository->getByAccountId($accountId)->willReturn([]);
        $useCase = new GetAccountExpensesUseCase(
            $this->expenseRepository->reveal()
        );
        $command = new GetAccountExpensesCommand($accountId);

        $result = $useCase($command);

        $this->assertCount(0, $result->expenses);
    }

    public function test_usecase_returns_same_expenses_than_repo()
    {
        $accountId = new Id(2);
        $expense1 = new Expense(
            new Id(1),
            $accountId,
            new Money(200, "EUR"),
            "expense 1",
            new \DateTime()
        );
        $expense2 = new Expense(
            new Id(2),
            $accountId,
            new Money(100, "EUR"),
            "expense 2",
            new \DateTime()
        );
        $this->expenseRepository->getByAccountId($accountId)
            ->shouldBeCalled()
            ->willReturn([
                $expense1, $expense2
            ]);
        $useCase = new GetAccountExpensesUseCase(
            $this->expenseRepository->reveal()
        );
        $command = new GetAccountExpensesCommand($accountId);

        $result = $useCase($command);

        $this->assertCount(2, $result->expenses);
    }
}
