<?php

namespace App\Application\UseCase\GetAccountExpenses;

use App\Domain\Repository\ExpenseRepositoryInterface;

class GetAccountExpensesUseCase
{
    public function __construct(
        private readonly ExpenseRepositoryInterface $expenseRepository
    ) {
    }

    public function __invoke(GetAccountExpensesCommand $command): AccountExpenses
    {
        $expenses = $this->expenseRepository->getByAccountId($command->accountId);

        return new AccountExpenses($expenses);
    }
}
