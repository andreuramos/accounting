<?php

namespace App\Invoice\Infrastructure\Controller;

use App\Shared\Domain\Exception\MissingMandatoryParameterException;
use App\Shared\Infrastructure\ApiResponse;
use App\Shared\Infrastructure\Controller\AuthorizedController;
use Symfony\Component\HttpFoundation\Request;

class CreateInvoiceController extends AuthorizedController
{
    private const MANDATORY_PARAMETERS = [
        'income_id', 'customer_name', 'customer_tax_name',
        'customer_tax_number', //'customer_tax_address',
        'customer_tax_zip_code'
    ];

    public function __invoke(Request $request): ApiResponse
    {
        $this->auth($request);
        $requestContent = json_decode($request->getContent(), true);
        $this->guardMandatoryParameters($requestContent);

        return new ApiResponse([
            'invoice_number' => 123,
        ]);
    }

    private function guardMandatoryParameters(mixed $requestContent): void
    {
        foreach (self::MANDATORY_PARAMETERS as $parameter) {
            if (!isset($requestContent[$parameter])) {
                throw new MissingMandatoryParameterException($parameter);
            }
        }
    }
}
