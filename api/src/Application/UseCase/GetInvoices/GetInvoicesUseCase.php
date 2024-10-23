<?php

namespace App\Application\UseCase\GetInvoices;

use App\Application\DTO\ExposableInvoices;

class GetInvoicesUseCase
{
    public function __invoke(GetInvoicesCommand $command): ExposableInvoices
    {
        return new ExposableInvoices([]);
    }
}
