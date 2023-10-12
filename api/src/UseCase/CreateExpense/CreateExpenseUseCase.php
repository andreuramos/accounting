<?php

namespace App\UseCase\CreateExpense;

use App\Domain\Expense;
use App\Domain\ExpenseRepositoryInterface;
use App\Domain\Id;
use App\Domain\Money;

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
