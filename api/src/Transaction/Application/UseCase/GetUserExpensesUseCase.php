<?php

namespace App\Transaction\Application\UseCase;

use App\Transaction\Application\Command\GetUserExpensesCommand;
use App\Transaction\Application\Result\UserExpenses;

class GetUserExpensesUseCase
{
    public function __invoke(GetUserExpensesCommand $command): UserExpenses
    {
        return new UserExpenses([]);
    }
}
