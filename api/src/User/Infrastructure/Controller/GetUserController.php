<?php

namespace App\User\Infrastructure\Controller;

use App\Shared\Infrastructure\Controller\AuthorizedController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GetUserController extends AuthorizedController
{
    public function __invoke(Request $request): Response
    {
        $this->auth($request);

        return new Response("Hola " . $this->authUser->email()->toString());
    }
}
