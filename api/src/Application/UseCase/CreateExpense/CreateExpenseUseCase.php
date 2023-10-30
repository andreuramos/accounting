<?php

namespace App\Application\UseCase\CreateExpense;

use App\Domain\Entities\Expense;
use App\Domain\Repository\ExpenseRepositoryInterface;
use App\Domain\ValueObject\Id;
use App\Domain\ValueObject\Money;

class CreateExpenseUseCase
{
    public function __construct(
        private readonly ExpenseRepositoryInterface $expenseRepository
    ) {
    }

    public function __invoke(CreateExpenseCommand $command): void
    {
        $expense = new Expense(
            new Id(null),
            $command->accountId,
            new Money($command->amountCents, 'EUR'),
            $command->description,
            date_create($command->date)
        );

        $this->expenseRepository->save($expense);
    }
}
