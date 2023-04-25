<?php

namespace App\Transaction\Infrastructure\Controller;

use App\Shared\Domain\Exception\MissingMandatoryParameterException;
use App\Shared\Infrastructure\ApiResponse;
use App\Shared\Infrastructure\Controller\AuthorizedController;
use Symfony\Component\HttpFoundation\Request;

class CreateExpenseController extends AuthorizedController
{
    public function __invoke(Request $request): ApiResponse
    {
        $this->auth($request);
        $requestContent = json_decode($request->getContent(), true);
        $this->guardMandatoryParameters($requestContent);

        return new ApiResponse([]);
    }

    private function guardMandatoryParameters(array $request)
    {
        if (!isset($request['amount'])) {
            throw new MissingMandatoryParameterException('amount');
        }
    }
}
