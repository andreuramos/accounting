<?php

namespace App\Infrastructure\Controller;

use App\Application\UseCase\EmitInvoice\EmitInvoiceCommand;
use App\Application\UseCase\EmitInvoice\EmitInvoiceUseCase;
use App\Domain\Exception\MissingMandatoryParameterException;
use App\Domain\Repository\UserRepositoryInterface;
use App\Infrastructure\ApiResponse;
use App\Infrastructure\Auth\JWTDecoder;
use Symfony\Component\HttpFoundation\Request;

class EmitInvoiceController extends AuthorizedController
{
    protected const MANDATORY_PARAMETERS = [
        'customer_name', 'customer_tax_name',
        'customer_tax_number', 'customer_tax_address',
        'customer_tax_zip_code', 'date', 'lines',
    ];
    private const LINE_PARAMETERS = [
        'amount', 'concept', 'quantity', 'vat_percent',
    ];

    public function __construct(
        JWTDecoder $tokenDecoder,
        UserRepositoryInterface $userRepository,
        private readonly EmitInvoiceUseCase $emitInvoiceUseCase,
    ) {
        parent::__construct($tokenDecoder, $userRepository);
    }

    public function __invoke(Request $request): ApiResponse
    {
        $this->auth($request);
        $requestContent = json_decode($request->getContent(), true);
        $this->guardMandatoryParameters($requestContent);
        $this->guardInvoiceLines($requestContent['lines']);

        $command = new EmitInvoiceCommand(
            $this->authUser,
            $requestContent['customer_name'],
            $requestContent['customer_tax_name'],
            $requestContent['customer_tax_number'],
            $requestContent['customer_tax_address'],
            $requestContent['customer_tax_zip_code'],
            date_create($requestContent['date']),
            $requestContent['lines'],
        );
        $invoiceNumber = ($this->emitInvoiceUseCase)($command);

        return new ApiResponse(
            [
            'invoice_number' => (string) $invoiceNumber,
            ]
        );
    }

    private function guardInvoiceLines(?array $requestLines): void
    {
        foreach ($requestLines as $line_number => $requestLine) {
            foreach (self::LINE_PARAMETERS as $parameter) {
                if (empty($requestLine[$parameter])) {
                    throw new MissingMandatoryParameterException($parameter . " (in the line $line_number)");
                }
            }
        }
    }
}
