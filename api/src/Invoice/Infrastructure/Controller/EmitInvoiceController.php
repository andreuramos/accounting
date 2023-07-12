<?php

namespace App\Invoice\Infrastructure\Controller;

use App\Invoice\Application\Command\EmitInvoiceCommand;
use App\Invoice\Application\UseCase\EmitInvoiceUseCase;
use App\Shared\Domain\Exception\MissingMandatoryParameterException;
use App\Shared\Domain\ValueObject\Id;
use App\Shared\Infrastructure\ApiResponse;
use App\Shared\Infrastructure\Controller\AuthorizedController;
use App\User\Domain\Model\UserRepositoryInterface;
use App\User\Infrastructure\Auth\JWTDecoder;
use Symfony\Component\HttpFoundation\Request;

class EmitInvoiceController extends AuthorizedController
{
    public function __construct(
        JWTDecoder $tokenDecoder,
        UserRepositoryInterface $userRepository,
        private readonly EmitInvoiceUseCase $emitInvoiceUseCase,
    ) {
        parent::__construct($tokenDecoder, $userRepository);
    }

    private const MANDATORY_PARAMETERS = [
        'customer_name', 'customer_tax_name',
        'customer_tax_number', 'customer_tax_address',
        'customer_tax_zip_code', 'date', 'lines',
    ];
    private const LINE_PARAMETERS = [
        'amount', 'concept', 'vat_percent',
    ];

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

        return new ApiResponse([
            'invoice_number' => (string) $invoiceNumber,
        ]);
    }

    private function guardMandatoryParameters(array $requestContent): void
    {
        foreach (self::MANDATORY_PARAMETERS as $parameter) {
            if (empty($requestContent[$parameter])) {
                throw new MissingMandatoryParameterException($parameter);
            }
        }
    }

    private function guardInvoiceLines(array $requestLines): void
    {
        foreach ($requestLines as $requestLine) {
            foreach (self::LINE_PARAMETERS as $parameter) {
                if (empty($requestLine[$parameter])) {
                    throw new MissingMandatoryParameterException($parameter);
                }
            }
        }
    }
}
