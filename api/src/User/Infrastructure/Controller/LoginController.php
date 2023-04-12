<?php

namespace App\User\Infrastructure\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class LoginController
{
    public function __invoke(Request $request): Response
    {
        return new Response();
    }
}
