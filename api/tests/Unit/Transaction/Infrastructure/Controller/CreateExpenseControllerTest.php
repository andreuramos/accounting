<?php

namespace Test\Unit\Transaction\Infrastructure\Controller;

use App\Application\UseCase\CreateExpense\CreateExpenseCommand;
use App\Application\UseCase\CreateExpense\CreateExpenseUseCase;
use App\Domain\Exception\InvalidCredentialsException;
use App\Domain\Exception\MissingMandatoryParameterException;
use App\Infrastructure\ApiResponse;
use App\Infrastructure\Controller\CreateExpenseController;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Symfony\Component\HttpFoundation\Request;
use Test\Unit\Shared\Infrastructure\Controller\AuthorizedControllerTest;

class CreateExpenseControllerTest extends AuthorizedControllerTest
{
    use ProphecyTrait;

    private $createExpenseUseCase;

    public function setUp(): void
    {
        parent::setUp();
        $this->createExpenseUseCase = $this->prophesize(CreateExpenseUseCase::class);
    }

    public function test_unauthorized_request_fails()
    {
        $request = new Request();
        $controller = $this->getController();

        $this->expectException(InvalidCredentialsException::class);

        $controller($request);
    }

    public function test_missing_amount_fails()
    {
        $request = $this->buildAuthorizedRequest([
            'description' => "ass",
            'date' => "2023-04-25",
        ]);
        $controller = $this->getController();

        $this->expectException(MissingMandatoryParameterException::class);

        $controller($request);
    }

    public function test_missing_description_fails()
    {
        $request = $this->buildAuthorizedRequest([
            'amount' => 30,
            'date' => "2023-04-25",
        ]);
        $controller = $this->getController();

        $this->expectException(MissingMandatoryParameterException::class);

        $controller($request);
    }

    public function test_missing_date_fails()
    {
        $request = $this->buildAuthorizedRequest([
            'amount' => 30,
            'description' => "rave",
        ]);
        $controller = $this->getController();

        $this->expectException(MissingMandatoryParameterException::class);

        $controller($request);
    }

    public function test_instantiates_command_and_calls_use_case()
    {
        $request = $this->buildAuthorizedRequest([
            'amount' => 30,
            'description' => "rave",
            'date' => '2022-04-26',
        ]);
        $controller = $this->getController();
        $this->createExpenseUseCase->__invoke(Argument::type(CreateExpenseCommand::class))
            ->shouldBeCalled();

        $response = $controller($request);

        $this->assertInstanceOf(ApiResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    private function getController(): CreateExpenseController
    {
        return new CreateExpenseController(
            $this->tokenDecoder->reveal(),
            $this->userRepository->reveal(),
            $this->createExpenseUseCase->reveal()
        );
    }
}
