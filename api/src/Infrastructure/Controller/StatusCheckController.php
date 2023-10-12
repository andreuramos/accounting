<?php

namespace App\Infrastructure\Controller;

use App\Infrastructure\ApiResponse;

class StatusCheckController
{
    public function __invoke(): ApiResponse
    {
        return new ApiResponse(['status' => "OK"]);
    }
}
