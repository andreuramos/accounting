<?php

namespace App\Infrastructure\Controller;

use App\Application\UseCase\GetInvoices\GetInvoicesCommand;
use App\Application\UseCase\GetInvoices\GetInvoicesUseCase;
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
        $command = new GetInvoicesCommand();
        
        $result = ($this->getInvoicesUseCase)($command);
        
        return new ApiResponse($result);
    }
}
