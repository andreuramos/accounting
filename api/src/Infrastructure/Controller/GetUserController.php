<?php

namespace App\Infrastructure\Controller;

use App\Application\UseCase\GetUser\GetUserUseCase;
use App\Domain\UserRepositoryInterface;
use App\Infrastructure\ApiResponse;
use App\Infrastructure\Auth\JWTDecoder;
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
