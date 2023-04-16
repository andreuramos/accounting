<?php

namespace App\User\Infrastructure\Controller;

use App\User\Infrastructure\Auth\ControllerAuthenticationTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GetUserController
{
    use ControllerAuthenticationTrait;

    public function __invoke(Request $request): Response
    {
        $this->auth($request);

        return new Response("Hola " . $this->authUser->email()->toString());
    }
}
