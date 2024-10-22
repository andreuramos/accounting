<?php

namespace App\Infrastructure\Controller;

use App\Application\UseCase\GetInvoices\GetInvoicesCommand;
use App\Application\UseCase\GetInvoices\GetInvoicesUseCase;
use App\Domain\Entities\Invoice;
use App\Domain\Entities\InvoiceAggregate;
use App\Domain\Repository\UserRepositoryInterface;
use App\Infrastructure\ApiResponse;
use App\Infrastructure\Auth\JWTDecoder;
use Symfony\Component\HttpFoundation\Request;

class GetInvoicesController extends AuthorizedController
{
    public function __construct(
        JWTDecoder $tokenDecoder,
        UserRepositoryInterface $userRepository,
        private readonly GetInvoicesUseCase $getInvoicesUseCase,
    ) {
        parent::__construct($tokenDecoder, $userRepository);
    }

    public function __invoke(Request $request): ApiResponse
    {
        $this->auth($request);
        $command = new GetInvoicesCommand(
            $this->authUser->accountId()
        );

        $result = ($this->getInvoicesUseCase)($command);

        $invocies = array_map(fn(InvoiceAggregate $invoice) => [
            'invoice_number' => (string) $invoice->invoiceNumber(),
        ]
            , $result);
        return new ApiResponse($invocies);
    }
}
