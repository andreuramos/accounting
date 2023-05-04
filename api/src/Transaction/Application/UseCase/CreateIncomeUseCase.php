<?php

namespace App\Transaction\Application\UseCase;

use App\Shared\Domain\ValueObject\Id;
use App\Transaction\Application\Command\CreateIncomeCommand;
use App\Transaction\Domain\Entity\Income;
use App\Transaction\Domain\Model\IncomeRepositoryInterface;
use App\Transaction\Domain\ValueObject\Money;

class CreateIncomeUseCase
{
    public function __construct(
        private readonly IncomeRepositoryInterface $incomeRepository
    ) {
    }

    public function __invoke(CreateIncomeCommand $command): void
    {
        $income = new Income(
            new Id(null),
            $command->user->id(),
            new Money($command->amountCents),
            $command->description,
            new \DateTime($command->date)
        );

        $this->incomeRepository->save($income);
    }
}