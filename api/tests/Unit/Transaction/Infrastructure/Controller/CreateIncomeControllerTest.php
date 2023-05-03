<?php

namespace Test\Unit\Transaction\Infrastructure\Controller;

use App\Shared\Domain\Exception\MissingMandatoryParameterException;
use App\Transaction\Application\Command\CreateIncomeCommand;
use App\Transaction\Application\UseCase\CreateIncomeUseCase;
use App\Transaction\Infrastructure\Controller\CreateIncomeController;
use App\User\Domain\Exception\InvalidCredentialsException;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\Request;
use Test\Unit\Shared\Infrastructure\Controller\AuthorizedControllerTest;

class CreateIncomeControllerTest extends AuthorizedControllerTest
{
    private $createIncomeUseCase;

    public function setUp(): void
    {
        parent::setUp();
        $this->createIncomeUseCase = $this->prophesize(CreateIncomeUseCase::class);
    }

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

    public function test_missing_description_fails()
    {
        $request = $this->buildRequest([
            'amount' => 100,
            'date' => '2023-05-03',
        ]);
        $request->headers->set('Authorization', 'Bearer ' . self::TOKEN);
        $controller = $this->buildController();

        $this->expectException(MissingMandatoryParameterException::class);

        $controller($request);
    }

    public function test_missing_date_fails()
    {
        $request = $this->buildRequest([
            'amount' => 100,
            'description' => 'missing date',
        ]);
        $request->headers->set('Authorization', 'Bearer ' . self::TOKEN);
        $controller = $this->buildController();

        $this->expectException(MissingMandatoryParameterException::class);

        $controller($request);
    }

    public function test_calls_usecase_with_command()
    {
        $request = $this->buildRequest([
            'amount' => 100,
            'description' => 'correct income',
            'date' => '2023-05-03'
        ]);
        $request->headers->set('Authorization', 'Bearer ' . self::TOKEN);
        $this->createIncomeUseCase->__invoke(Argument::type(CreateIncomeCommand::class))
            ->shouldBeCalled();
        $controller = $this->buildController();

        $controller($request);
    }

    private function buildController(): CreateIncomeController
    {
        return new CreateIncomeController(
            $this->tokenDecoder->reveal(),
            $this->userRepository->reveal(),
            $this->createIncomeUseCase->reveal(),
        );
    }


    private function buildRequest(array $content): Request
    {
        return new Request(
            [], [], [], [], [], [], json_encode($content)
        );
    }
}
