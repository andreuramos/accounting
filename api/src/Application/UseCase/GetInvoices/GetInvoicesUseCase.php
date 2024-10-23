<?php

namespace App\Application\UseCase\GetInvoices;

class GetInvoicesUseCase
{
    public function __invoke(GetInvoicesCommand $command): ExposableInvoices
    {
        return new ExposableInvoices([]);
    }
}