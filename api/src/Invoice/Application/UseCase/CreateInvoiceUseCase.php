<?php

namespace App\Invoice\Application\UseCase;

use App\Invoice\Application\Command\CreateInvoiceCommand;
use App\Invoice\Domain\ValueObject\InvoiceNumber;
use App\Transaction\Domain\Exception\IncomeNotFoundException;
use App\Transaction\Domain\Model\IncomeRepositoryInterface;

class CreateInvoiceUseCase
{
    public function __construct(
        private readonly IncomeRepositoryInterface $incomeRepository,
    ) {
    }
    public function __invoke(CreateInvoiceCommand $command): InvoiceNumber
    {
        $income = $this->incomeRepository->getByIdOrFail($command->incomeId);
        if ($income->userId->getInt() !== $command->user->id()->getInt()) {
            throw new IncomeNotFoundException();
        }

        return new InvoiceNumber('');
    }
}
