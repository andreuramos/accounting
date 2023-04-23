<?php

namespace App\User\Infrastructure\Controller;

use App\Shared\Domain\Exception\MissingMandatoryParameterException;
use App\User\Application\Command\RefreshTokensCommand;
use App\User\Application\UseCase\RefreshTokensUseCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RefreshTokenController
{
    public function __construct(private readonly RefreshTokensUseCase $refreshTokensUseCase)
    {
    }

    public function __invoke(Request $request): Response
    {
        $refreshToken = $this->getRefreshToken($request);

        $command = new RefreshTokensCommand($refreshToken);
        $response = ($this->refreshTokensUseCase)($command);

        return new Response(json_encode([
            "token" => (string) $response->token,
            "refresh" => (string) $response->refresh
        ], JSON_THROW_ON_ERROR), 200, [
            'Content-Type' => 'application/json'
        ]);
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
