<?php

namespace App\Domain\Exception;

class InvalidDeclarationPeriodException extends \Exception
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
