<?php

namespace App\Transaction\Infrastructure\Controller;

use App\Shared\Infrastructure\ApiResponse;
use Symfony\Component\HttpFoundation\Request;

class CreateExpenseController
{
    public function __invoke(Request $request): ApiResponse
    {
        return new ApiResponse([]);
    }
}
