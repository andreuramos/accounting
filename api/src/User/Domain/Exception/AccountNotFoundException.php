<?php

namespace App\User\Domain\Exception;

use Throwable;

class AccountNotFoundException extends \Exception
{
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        $message = 'account not found owned by ' . $message;
        parent::__construct($message, $code, $previous);
    }
}
