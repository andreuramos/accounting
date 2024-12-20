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

        [$from, $to, $emittedBy, $receivedBy] = $this->extractFilters($request);
        $command = new GetInvoicesCommand(
            $this->authUser->accountId(),
            $from,
            $to,
            $emittedBy,
            $receivedBy,
        );

        $result = ($this->getInvoicesUseCase)($command);

        return new ApiResponse($result->__toArray());
    }

    private function extractFilters(Request $request): array
    {
        $from = $request->query->get('from') ?
            new \DateTime($request->query->get('from')) : null;
        $to = $request->query->get('to') ?
            new \DateTime($request->query->get('to')) : null;
        $emittedBy = $request->query->get('emitted_by');
        $receivedBy = $request->query->get('received_by');

        return array($from, $to, $emittedBy, $receivedBy);
    }
}
