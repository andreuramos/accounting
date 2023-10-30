<?php

namespace App\Domain\Exception;

use App\User\Domain\Exception\Throwable;

class UserAlreadyExistsException extends \Exception
{
    private const MESSAGE = "User already exists";

    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct(self::MESSAGE, $code, $previous);
    }
}
