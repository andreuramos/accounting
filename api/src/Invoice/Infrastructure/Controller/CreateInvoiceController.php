<?php

namespace App\Invoice\Infrastructure\Controller;

use App\Invoice\Application\Command\CreateInvoiceCommand;
use App\Invoice\Application\UseCase\CreateInvoiceUseCase;
use App\Shared\Domain\Exception\MissingMandatoryParameterException;
use App\Shared\Domain\ValueObject\Id;
use App\Shared\Infrastructure\ApiResponse;
use App\Shared\Infrastructure\Controller\AuthorizedController;
use App\User\Domain\Model\UserRepositoryInterface;
use App\User\Infrastructure\Auth\JWTDecoder;
use Symfony\Component\HttpFoundation\Request;

class CreateInvoiceController extends AuthorizedController
{
    public function __construct(
        JWTDecoder $tokenDecoder,
        UserRepositoryInterface $userRepository,
        private readonly CreateInvoiceUseCase $createInvoiceUseCase,
    ) {
        parent::__construct($tokenDecoder, $userRepository);
    }

    private const MANDATORY_PARAMETERS = [
        'income_id', 'customer_name', 'customer_tax_name',
        'customer_tax_number', 'customer_tax_address',
        'customer_tax_zip_code'
    ];

    public function __invoke(Request $request): ApiResponse
    {
        $this->auth($request);
        $requestContent = json_decode($request->getContent(), true);
        $this->guardMandatoryParameters($requestContent);

        $command = new CreateInvoiceCommand(
            new Id($requestContent['income_id'])
        );
        $invoiceNumber = ($this->createInvoiceUseCase)($command);

        return new ApiResponse([
            'invoice_number' => (string) $invoiceNumber,
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
