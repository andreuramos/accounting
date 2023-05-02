<?php

namespace App\Transaction\Infrastructure;

use App\Shared\Infrastructure\ApiResponse;
use App\Shared\Infrastructure\Controller\AuthorizedController;
use Symfony\Component\HttpFoundation\Request;

class CreateIncomeController extends AuthorizedController
{
    public function __invoke(Request $request): ApiResponse
    {
        return new ApiResponse([]);
    }
}
