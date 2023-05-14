<?php

namespace App\Invoice\Application\UseCase;

use App\Invoice\Application\Command\CreateInvoiceCommand;
use App\Invoice\Domain\ValueObject\InvoiceNumber;

class CreateInvoiceUseCase
{
    public function __invoke(CreateInvoiceCommand $command): InvoiceNumber
    {
        return new InvoiceNumber('');
    }
}
