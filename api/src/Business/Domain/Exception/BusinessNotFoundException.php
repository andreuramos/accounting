<?php

namespace App\Business\Domain\Exception;

use Throwable;

class BusinessNotFoundException extends \Exception
{
    public function __construct($id = null, int $code = 0, ?Throwable $previous = null)
    {
        $message = "Business not found with id: " . $id;
        parent::__construct($message, $code, $previous);
    }
}
