<?php

namespace App\Transaction\Application\UseCase;

use App\Transaction\Application\Command\GetUserIncomesCommand;
use App\Transaction\Application\Result\UserIncomes;

class GetUserIncomesUseCase
{
    public function __invoke(GetUserIncomesCommand $command): UserIncomes
    {
    }
}
