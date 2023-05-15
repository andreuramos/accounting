<?php

namespace App\Transaction\Domain\Exception;

use Throwable;

class IncomeNotFoundException extends \Exception
{
    public function __construct($id = "", int $code = 0, ?Throwable $previous = null)
    {
        $message = "Income not found with ID: " . $id;
        parent::__construct($message, $code, $previous);
    }
}
