<?php

namespace App\User\Domain\Exception;

class InvalidEmailException extends \Exception
{

    private const MESSAGE = 'Email is invalid';

    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct(self::MESSAGE, $code, $previous);
    }
}
