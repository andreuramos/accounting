<?php

namespace App\Tax\Infrastructure\Controller;

use App\Shared\Infrastructure\ApiResponse;
use App\Shared\Infrastructure\Controller\AuthorizedController;
use Symfony\Component\HttpFoundation\Request;

class SetTaxDataController extends AuthorizedController
{
    public function __invoke(Request $request): ApiResponse
    {
        $this->auth($request);
        return new ApiResponse([]);
    }
}
