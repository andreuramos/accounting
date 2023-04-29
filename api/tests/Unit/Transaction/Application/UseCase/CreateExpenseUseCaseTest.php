<?php

namespace Test\Unit\Transaction\Application\UseCase;

use App\Shared\Domain\ValueObject\Id;
use App\Transaction\Application\Command\CreateExpenseCommand;
use App\Transaction\Application\UseCase\CreateExpenseUseCase;
use App\Transaction\Domain\Entity\Expense;
use App\Transaction\Domain\Model\ExpenseRepositoryInterface;
use App\Transaction\Domain\ValueObject\Money;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class CreateExpenseUseCaseTest extends TestCase
{
    use ProphecyTrait;

    private $expenseRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->expenseRepository = $this->prophesize(ExpenseRepositoryInterface::class);
    }

    public function test_repository_is_called_with_expense_object()
    {
        $command = new CreateExpenseCommand(
            1,
            "porros",
            '2022-04-27',
            new Id(1)
        );
        $expense = new Expense(
            new Id(null),
            new Id(1),
            new Money(1, "EUR"),
            "porros",
            date_create('2022-04-27')
        );
        $this->expenseRepository->save($expense)->shouldBeCalled();
        $useCase = new CreateExpenseUseCase($this->expenseRepository->reveal());

        $useCase($command);
    }
}
