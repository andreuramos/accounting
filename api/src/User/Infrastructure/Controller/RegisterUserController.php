<?php

namespace App\User\Infrastructure\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RegisterUserController
{
    public function __invoke(Request $request): Response
    {
        return new Response(json_encode([]));
    }
}
