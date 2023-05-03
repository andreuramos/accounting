<?php

namespace Test\Unit\Transaction\Infrastructure\Controller;

use App\Shared\Domain\Exception\MissingMandatoryParameterException;
use App\Transaction\Infrastructure\Controller\CreateIncomeController;
use App\User\Domain\Exception\InvalidCredentialsException;
use Symfony\Component\HttpFoundation\Request;
use Test\Unit\Shared\Infrastructure\Controller\AuthorizedControllerTest;

class CreateIncomeControllerTest extends AuthorizedControllerTest
{
    public function test_unauthorized_fails()
    {
        $request = new Request();
        $controller = $this->buildController();

        $this->expectException(InvalidCredentialsException::class);

        $controller($request);
    }

    public function test_missing_amount_fails()
    {
        $request = $this->buildRequest([
            'description' => "test_missing_amount",
            'date' => '2023-05-03',
        ]);
        $request->headers->set('Authorization', 'Bearer ' . self::TOKEN);
        $controller = $this->buildController();

        $this->expectException(MissingMandatoryParameterException::class);

        $controller($request);
    }

    private function buildController(): CreateIncomeController
    {
        return new CreateIncomeController(
            $this->tokenDecoder->reveal(),
            $this->userRepository->reveal(),
        );
    }


    private function buildRequest(array $content): Request
    {
        return new Request(
            [], [], [], [], [], [], json_encode($content)
        );
    }
}
