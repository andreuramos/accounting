<?php

namespace App\Infrastructure\Controller;

use App\Domain\Exception\MissingMandatoryParameterException;
use App\Infrastructure\ApiResponse;
use Symfony\Component\HttpFoundation\Request;

class Manual303FormController
{
    private const MANDATORY_PARAMETERS = [
        "tax_name", "tax_id", "year", "quarter", "accrued_base", "accrued_tax",
        "deductible_base", "deductible_tax", "iban", 
    ];
    
    public function __invoke(Request $request): ApiResponse
    {
        $requestContent = json_decode($request->getContent(), true);
        $this->guardMandatoryParameters($requestContent);
        
        
        return new ApiResponse([]);
    }

    private function guardMandatoryParameters(mixed $requestContent): void
    {
        foreach (self::MANDATORY_PARAMETERS as $parameter) {
            if(empty($requestContent[$parameter])) {
                throw new MissingMandatoryParameterException($parameter);
            }
        }
    }
}
