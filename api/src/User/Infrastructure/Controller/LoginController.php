<?php

namespace App\User\Infrastructure\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\MissingMandatoryParametersException;

class LoginController
{
    public function __invoke(Request $request): Response
    {
        $requestContent = $this->getRequestBody($request);
        $this->guardRequiredParams($requestContent);

        return new Response();
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
            throw new MissingMandatoryParametersException("email");
        }
        if (!array_key_exists('password', $requestContent)) {
            throw new MissingMandatoryParametersException("email");
        }
    }
}
