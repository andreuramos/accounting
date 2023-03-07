<?php

namespace App\User\Domain\Exception;

class UserAlreadyExistsException extends \Exception
{
    private const MESSAGE = "User already exists";

    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct(self::MESSAGE, $code, $previous);
    }
}
