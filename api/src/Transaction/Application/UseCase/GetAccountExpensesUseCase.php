<?php

namespace App\Transaction\Application\UseCase;

use App\Transaction\Application\Command\GetAccountExpensesCommand;
use App\Transaction\Application\Result\UserExpenses;
use App\Transaction\Domain\Model\ExpenseRepositoryInterface;

class GetAccountExpensesUseCase
{
    public function __construct(
        private readonly ExpenseRepositoryInterface $expenseRepository
    ) {
    }

    public function __invoke(GetAccountExpensesCommand $command): UserExpenses
    {
        $expenses = $this->expenseRepository->getByAccountId($command->accountId);

        return new UserExpenses($expenses);
    }
}