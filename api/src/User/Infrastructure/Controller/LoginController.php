<?php

namespace App\User\Infrastructure\Controller;

use App\Shared\Domain\Exception\MissingMandatoryParameterException;
use App\User\Application\Command\LoginCommand;
use App\User\Application\UseCase\LoginUseCase;
use App\User\Domain\Exception\InvalidCredentialsException;
use App\User\Domain\ValueObject\Email;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use TheSeer\Tokenizer\Exception;

class LoginController
{
    public function __construct(private readonly LoginUseCase $loginUseCase)
    {
    }

    public function __invoke(Request $request): Response
    {
        $requestContent = $this->getRequestBody($request);
        $this->guardRequiredParams($requestContent);

        $command = new LoginCommand(
            new Email($requestContent['email']),
            $requestContent['password']
        );

        try {
            $result = ($this->loginUseCase)($command);

            return new Response(json_encode([
                "token" => $result->token,
                "refresh" => $result->refresh,
            ], JSON_THROW_ON_ERROR), 200, [
                'Content-Type' => 'application/json'
            ]);
        } catch (InvalidCredentialsException $exception) {
            return new Response("", 401);
        } catch (Exception $anythingElse) {
            return new Response("", 400);
        }

    }

    private function getRequestBody(Request $request): array
    {
        return json_decode(
            $request->getContent(),
            true,
            512,
            JSON_THROW_ON_ERROR
        );
    }

    private function guardRequiredParams(array $requestContent)
    {
        if (!array_key_exists('email', $requestContent)) {
            throw new MissingMandatoryParameterException("email");
        }
        if (!array_key_exists('password', $requestContent)) {
            throw new MissingMandatoryParameterException("email");
        }
    }
}
