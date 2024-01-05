<?php

namespace App\Infrastructure\Controller;

use App\Application\UseCase\ReceiveInvoice\ReceiveInvoiceCommand;
use App\Application\UseCase\ReceiveInvoice\ReceiveInvoiceUseCase;
use App\Domain\Exception\MissingMandatoryParameterException;
use App\Domain\Repository\UserRepositoryInterface;
use App\Infrastructure\ApiResponse;
use App\Infrastructure\Auth\JWTDecoder;
use Symfony\Component\HttpFoundation\Request;

class ReceiveInvoiceController extends AuthorizedController
{
    const MANDATORY_PARAMETERS = [
        "provider_name", "provider_tax_name", "provider_tax_number",
        "provider_tax_address", "provider_tax_zip_code", "invoice_number",
        "description", "date", "amount", "taxes"
    ];
    
    public function __construct(
        JWTDecoder $tokenDecoder, 
        UserRepositoryInterface $userRepository,
        private readonly ReceiveInvoiceUseCase $useCase,
    ) {
        parent::__construct($tokenDecoder, $userRepository);
    }

    public function __invoke(Request $request): ApiResponse
    {
        $this->auth($request);
        $requestContent = json_decode($request->getContent(), true);
        $this->guardMandatoryParameters($requestContent);
        
        $command = new ReceiveInvoiceCommand(
            $requestContent['provider_name'],
            $requestContent['provider_tax_name'],
            $requestContent['provider_tax_number'],
            $requestContent['provider_tax_address'],
            $requestContent['provider_tax_zip_code'],
            $requestContent['invoice_number'],
            $requestContent['description'],
            $requestContent['date'],
            (int) $requestContent['amount'],
            (int) $requestContent['taxes'],
        );
        ($this->useCase)($command);

        return new ApiResponse([], 201);
    }

    private function guardMandatoryParameters(array $requestContent): void
    {
        foreach (self::MANDATORY_PARAMETERS as $parameter) {
            if (empty($requestContent[$parameter])) {
                throw new MissingMandatoryParameterException($parameter);
            }
        }
    }
}
