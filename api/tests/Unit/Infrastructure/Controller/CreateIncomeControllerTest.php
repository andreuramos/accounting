<?php

namespace Test\Unit\Infrastructure\Controller;

use App\Application\UseCase\CreateIncome\CreateIncomeCommand;
use App\Application\UseCase\CreateIncome\CreateIncomeUseCase;
use App\Domain\Exception\InvalidCredentialsException;
use App\Domain\Exception\MissingMandatoryParameterException;
use App\Domain\ValueObject\Id;
use App\Infrastructure\Controller\CreateIncomeController;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\Request;

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
        $request = $this->buildAuthorizedRequest([
            'description' => "test_missing_amount",
            'date' => '2023-05-03',
        ]);
        $controller = $this->buildController();

        $this->expectException(MissingMandatoryParameterException::class);

        $controller($request);
    }

    public function test_missing_description_fails()
    {
        $request = $this->buildAuthorizedRequest([
            'amount' => 100,
            'date' => '2023-05-03',
        ]);
        $controller = $this->buildController();

        $this->expectException(MissingMandatoryParameterException::class);

        $controller($request);
    }

    public function test_missing_date_fails()
    {
        $request = $this->buildAuthorizedRequest([
            'amount' => 100,
            'description' => 'missing date',
        ]);
        $controller = $this->buildController();

        $this->expectException(MissingMandatoryParameterException::class);

        $controller($request);
    }

    public function test_calls_usecase_with_command()
    {
        $request = $this->buildAuthorizedRequest([
            'amount' => 100,
            'description' => 'correct income',
            'date' => '2023-05-03'
        ]);
        $this->createIncomeUseCase->__invoke(Argument::type(CreateIncomeCommand::class))
            ->shouldBeCalled();
        $controller = $this->buildController();

        $controller($request);
    }

    public function test_returns_usecase_created_id()
    {
        $request = $this->buildAuthorizedRequest([
            'amount' => 100,
            'description' => 'correct income',
            'date' => '2023-05-14'
        ]);
        $this->createIncomeUseCase->__invoke(Argument::type(CreateIncomeCommand::class))
            ->willReturn(new Id(23));
        $controller = $this->buildController();

        $response = $controller($request);

        $decodedContent = json_decode($response->getContent(), true);
        $this->assertEquals(23, $decodedContent['id']);
    }

    private function buildController(): CreateIncomeController
    {
        return new CreateIncomeController(
            $this->tokenDecoder->reveal(),
            $this->userRepository->reveal(),
            $this->createIncomeUseCase->reveal(),
        );
    }
}
