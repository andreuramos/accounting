<?php

namespace App\User\Domain\Exception;

class UserNotFoundException extends \Exception
{
    public function __construct(string $field, string $value)
    {
        $message = "User not found by " . $field . ": " . $value;
        parent::__construct($message);
    }
}
