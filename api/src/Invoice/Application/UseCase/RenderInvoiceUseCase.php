<?php

namespace App\Invoice\Application\UseCase;

use App\Invoice\Application\Command\RenderInvoiceCommand;

class RenderInvoiceUseCase
{
    public function __invoke(RenderInvoiceCommand $command): void
    {
    }
}
