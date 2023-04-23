<?php

namespace App\Shared\Infrastructure\Controller;

use App\Shared\Infrastructure\ApiResponse;
use App\User\Domain\ValueObject\Email;

class SharedDomainController
{
    public function __invoke(): ApiResponse
    {
        return new ApiResponse([
            'email_regex' => Email::VALIDATION_REGEX,
        ]);
    }
}
