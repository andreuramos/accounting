<?php

namespace App\Domain\Exception;

use App\Shared\Domain\Exception\Throwable;

class MissingMandatoryParameterException extends \Exception
{
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct("Missing mandatory parameter: " . $message, $code, $previous);
    }
}
