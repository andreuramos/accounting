<?php

namespace App\Domain\Exception;

class InvalidArgumentException extends \Exception
{
    public function __construct(string $argumentName, string $errorMessage)
    {
        $message = $argumentName . ': ' . $errorMessage;
        parent::__construct($message);
    }
}
