<?php

namespace App\Infrastructure\Controller;

use App\Domain\ValueObject\Email;
use App\Infrastructure\ApiResponse;

class SharedDomainController
{
    public function __invoke(): ApiResponse
    {
        return new ApiResponse([
            'email_regex' => Email::VALIDATION_REGEX,
        ]);
    }
}
