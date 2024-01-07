<?php

namespace App\Infrastructure\Controller;

use App\Application\UseCase\RefreshToken\RefreshTokensCommand;
use App\Application\UseCase\RefreshToken\RefreshTokensUseCase;
use App\Domain\Exception\MissingMandatoryParameterException;
use App\Infrastructure\ApiResponse;
use Symfony\Component\HttpFoundation\Request;

class RefreshTokenController
{
    public function __construct(private readonly RefreshTokensUseCase $refreshTokensUseCase)
    {
    }

    public function __invoke(Request $request): ApiResponse
    {
        $refreshToken = $this->getRefreshToken($request);

        $command = new RefreshTokensCommand($refreshToken);
        $response = ($this->refreshTokensUseCase)($command);

        return new ApiResponse(
            [
            "token" => (string) $response->token,
            "refresh" => (string) $response->refresh
            ]
        );
    }

    private function getRefreshToken(Request $request): string
    {
        $content = json_decode($request->getContent(), true);
        if (!isset($content['refresh_token'])) {
            throw new MissingMandatoryParameterException("refresh_token");
        }

        return $content['refresh_token'];
    }
}
