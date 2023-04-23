<?php

namespace App\User\Infrastructure\Controller;

use App\Shared\Infrastructure\ApiResponse;
use App\Shared\Infrastructure\Controller\AuthorizedController;
use App\User\Application\UseCase\GetUserUseCase;
use App\User\Domain\Model\UserRepositoryInterface;
use App\User\Infrastructure\Auth\JWTDecoder;
use Symfony\Component\HttpFoundation\Request;

class GetUserController extends AuthorizedController
{
    public function __construct(
        JWTDecoder $tokenDecoder,
        UserRepositoryInterface $userRepository,
        private readonly GetUserUseCase $getUserUseCase
    ) {
        parent::__construct($tokenDecoder, $userRepository);
    }

    public function __invoke(Request $request): ApiResponse
    {
        $this->auth($request);

        $result = ($this->getUserUseCase)($this->authUser->email());

        return new ApiResponse($result);
    }
}
