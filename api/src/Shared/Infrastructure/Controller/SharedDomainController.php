<?php

namespace App\Shared\Infrastructure\Controller;

use Symfony\Component\HttpFoundation\Response;

class SharedDomainController
{
    public function __invoke()
    {
        return new Response("OK");
    }
}
