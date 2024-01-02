<?php

namespace App\Infrastructure\Controller;

use App\Application\UseCase\Form303\Manual303FormCommand;
use App\Application\UseCase\Form303\Manual303FormUseCase;
use App\Domain\Exception\MissingMandatoryParameterException;
use App\Infrastructure\FileApiResponse;
use Symfony\Component\HttpFoundation\Request;

class Manual303FormController
{
    private const MANDATORY_PARAMETERS = [
        "tax_name", "tax_id", "year", "quarter", "accrued_base", "accrued_tax",
        "deductible_base", "deductible_tax", "iban",
    ];

    public function __construct(
        private readonly Manual303FormUseCase $useCase,
    ) {
    }

    public function __invoke(Request $request): FileApiResponse
    {
        $requestContent = json_decode($request->getContent(), true);
        $this->guardMandatoryParameters($requestContent);
        $command = new Manual303FormCommand(
            $requestContent['tax_name'],
            $requestContent['tax_id'],
            $requestContent['year'],
            $requestContent['quarter'],
            $requestContent['accrued_base'],
            $requestContent['accrued_tax'],
            $requestContent['deductible_base'],
            $requestContent['deductible_tax'],
            $requestContent['iban']
        );

        $importableFile = ($this->useCase)($command);

        return new FileApiResponse((string) $importableFile, "holaputo.txt", "text/plain");
    }

    private function guardMandatoryParameters(mixed $requestContent): void
    {
        foreach (self::MANDATORY_PARAMETERS as $parameter) {
            if (empty($requestContent[$parameter])) {
                throw new MissingMandatoryParameterException($parameter);
            }
        }
    }
}
