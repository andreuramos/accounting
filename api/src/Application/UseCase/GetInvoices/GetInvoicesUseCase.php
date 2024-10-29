<?php

namespace App\Application\UseCase\GetInvoices;

use App\Application\DTO\ExposableInvoices;
use App\Domain\Criteria\InvoiceCriteria;
use App\Domain\Repository\InvoiceAggregateRepositoryInterface;

class GetInvoicesUseCase
{
    public function __construct(
        private readonly InvoiceAggregateRepositoryInterface $invoiceAggregateRepository,
    ) {
    }
    
    public function __invoke(GetInvoicesCommand $command): ExposableInvoices
    {
        $criteria = $this->buildCriteria($command);
        
        $invoices = $this->invoiceAggregateRepository->getByCriteria($criteria);
        
        return new ExposableInvoices($invoices);
    }

    private function buildCriteria(GetInvoicesCommand $command): InvoiceCriteria
    {
        $criteria = new InvoiceCriteria();
        $criteria->filterByAccountId($command->accountId);
        
        return $criteria;
    }
}
