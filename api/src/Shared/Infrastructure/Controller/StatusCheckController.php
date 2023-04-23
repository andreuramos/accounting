<?php

namespace App\Shared\Infrastructure\Controller;

use App\Shared\Infrastructure\ApiResponse;

class StatusCheckController
{
    public function __invoke(): ApiResponse
    {
        return new ApiResponse(['status' => "OK"]);
    }
}
