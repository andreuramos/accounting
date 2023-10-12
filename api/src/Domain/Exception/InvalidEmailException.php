<?php

namespace App\Domain\Exception;

use App\User\Domain\Exception\Throwable;

class InvalidEmailException extends \Exception
{
    private const MESSAGE = 'Email is invalid';

    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct(self::MESSAGE, $code, $previous);
    }
}
