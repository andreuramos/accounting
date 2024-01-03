<?php

namespace App\Infrastructure\Controller;

use App\Infrastructure\ApiResponse;
use Symfony\Component\HttpFoundation\Request;

class ReceiveInvoiceController extends AuthorizedController
{
    public function __invoke(Request $request): ApiResponse
    {
        $this->auth($request);

        return new ApiResponse([], 201);
    }
}
