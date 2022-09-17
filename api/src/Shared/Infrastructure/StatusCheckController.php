<?php

namespace App\Shared\Infrastructure;

use Symfony\Component\HttpFoundation\Response;

class StatusCheckController
{
    public function __invoke()
    {
        return new Response("OK");
    }
}
