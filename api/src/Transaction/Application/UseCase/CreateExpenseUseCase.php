<?php

namespace App\Transaction\Application\UseCase;

use App\Shared\Domain\ValueObject\Id;
use App\Transaction\Application\Command\CreateExpenseCommand;
use App\Transaction\Domain\Entity\Expense;
use App\Transaction\Domain\Model\ExpenseRepositoryInterface;
use App\Transaction\Domain\ValueObject\Money;

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
