<?php

namespace Test\Unit\Transaction\Infrastructure\Controller;

use App\Shared\Domain\Exception\MissingMandatoryParameterException;
use App\Shared\Infrastructure\ApiResponse;
use App\Transaction\Application\Command\CreateExpenseCommand;
use App\Transaction\Application\UseCase\CreateExpenseUseCase;
use App\Transaction\Infrastructure\Controller\CreateExpenseController;
use App\User\Domain\Exception\InvalidCredentialsException;
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
        $request = $this->buildRequest([
            'description' => "ass",
            'date' => "2023-04-25",
        ], ['Authorization' => 'Bearer ' . self::TOKEN]);
        $controller = $this->getController();

        $this->expectException(MissingMandatoryParameterException::class);

        $controller($request);
    }

    public function test_missing_description_fails()
    {
        $request = $this->buildRequest([
            'amount' => 30,
            'date' => "2023-04-25",
        ], ['Authorization' => 'Bearer ' . self::TOKEN]);
        $controller = $this->getController();

        $this->expectException(MissingMandatoryParameterException::class);

        $controller($request);
    }

    public function test_missing_date_fails()
    {
        $request = $this->buildRequest([
            'amount' => 30,
            'description' => "rave",
        ], ['Authorization' => 'Bearer ' . self::TOKEN]);
        $controller = $this->getController();

        $this->expectException(MissingMandatoryParameterException::class);

        $controller($request);
    }

    public function test_instantiates_command_and_calls_use_case()
    {
        $request = $this->buildRequest([
            'amount' => 30,
            'description' => "rave",
            'date' => '2022-04-26',
        ], ['Authorization' => 'Bearer ' . self::TOKEN]);
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

    private function buildRequest(array $content, array $headers): Request
    {
        $request = new Request(
            [], [], [], [], [], [], json_encode($content)
        );
        foreach ($headers as $header => $value) {
            $request->headers->set($header, $value);
        }
        return $request;
    }
}
