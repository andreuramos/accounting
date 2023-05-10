<?php

namespace App\Tax\Infrastructure\Controller;

use App\Shared\Domain\Exception\MissingMandatoryParameterException;
use App\Shared\Infrastructure\ApiResponse;
use App\Shared\Infrastructure\Controller\AuthorizedController;
use Symfony\Component\HttpFoundation\Request;

class SetTaxDataController extends AuthorizedController
{
    private const MANDATORY_PARAMETERS = [
        'tax_name', 'tax_number', 'tax_address_street',
        'tax_address_zip_code', 'tax_address_region'
    ];

    public function __invoke(Request $request): ApiResponse
    {
        $this->auth($request);
        $content = json_decode($request->getContent(), true);
        $this->guardMandatoryParameters($content);

        return new ApiResponse([]);
    }

    private function guardMandatoryParameters(array $content): void
    {
        foreach (self::MANDATORY_PARAMETERS as $parameter) {
            if (!array_key_exists($parameter, $content)) {
                throw new MissingMandatoryParameterException($parameter);
            }
        }
    }
}
