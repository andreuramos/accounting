<?php

namespace App\Domain\Exception;

use App\Domain\ValueObject\InvoiceNumber;

class InvoiceAlreadyExistsException extends \Exception
{
    public function __construct(string $taxNumber, InvoiceNumber $invoiceNumber)
    {
        $message = "Invoice with invoice number $invoiceNumber emitted by $taxNumber already exists";
        parent::__construct($message, 400);
    }
}
