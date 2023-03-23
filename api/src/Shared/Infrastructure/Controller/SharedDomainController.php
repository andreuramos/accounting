<?php

namespace App\Shared\Infrastructure\Controller;

use App\User\Domain\ValueObject\Email;
use Symfony\Component\HttpFoundation\Response;

class SharedDomainController
{
    public function __invoke()
    {
        $response = new Response(json_encode([
            'email_regex' => Email::VALIDATION_REGEX,
        ], JSON_THROW_ON_ERROR));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}
