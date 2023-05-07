<?php

namespace App\Transaction\Application\UseCase;

use App\Transaction\Application\Command\GetUserIncomesCommand;
use App\Transaction\Application\Result\UserIncomes;
use App\Transaction\Domain\Model\IncomeRepositoryInterface;

class GetUserIncomesUseCase
{
    public function __construct(
        private readonly IncomeRepositoryInterface $incomeRepository
    ) {
    }

    public function __invoke(GetUserIncomesCommand $command): UserIncomes
    {
        $incomes = $this->incomeRepository->getByUser(
            $command->user
        );

        return new UserIncomes($incomes);
    }
}
