<?php

namespace App\Transaction\Application\UseCase;

use App\Transaction\Application\Command\GetUserExpensesCommand;
use App\Transaction\Application\Result\UserExpenses;
use App\Transaction\Domain\Model\ExpenseRepositoryInterface;

class GetUserExpensesUseCase
{
    public function __construct(
        private readonly ExpenseRepositoryInterface $expenseRepository
    ) {
    }

    public function __invoke(GetUserExpensesCommand $command): UserExpenses
    {
        $expenses = $this->expenseRepository->getByUser($command->user);

        return new UserExpenses($expenses);
    }
}
