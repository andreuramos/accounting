<?php

namespace App\Domain\Exception;

use App\Invoice\Domain\Exception\Throwable;

class InvoiceNotFoundException extends \Exception
{
    public function __construct(string $invoiceNumber, int $code = 0, ?Throwable $previous = null)
    {
        $message = "Invoice $invoiceNumber not found";
        parent::__construct($message, $code, $previous);
    }
}
