<?php

namespace App\Transaction\Application\UseCase;

use App\Transaction\Application\Command\GetAccountIncomesCommand;
use App\Transaction\Application\Result\UserIncomes;
use App\Transaction\Domain\Model\IncomeRepositoryInterface;

class GetAccountIncomesUseCase
{
    public function __construct(
        private readonly IncomeRepositoryInterface $incomeRepository
    ) {
    }

    public function __invoke(GetAccountIncomesCommand $command): UserIncomes
    {
        $incomes = $this->incomeRepository->getByAccountId(
            $command->accountId
        );

        return new UserIncomes($incomes);
    }
}
