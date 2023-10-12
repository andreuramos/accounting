<?php

namespace Test\Unit\Transaction\Infrastructure\Controller;

use App\Domain\Exception\InvalidCredentialsException;
use App\Domain\Id;
use App\Domain\Income;
use App\Domain\Money;
use App\Infrastructure\ApiResponse;
use App\Infrastructure\Controller\GetIncomesController;
use App\UseCase\GetAccountIncomes\AccountIncomes;
use App\UseCase\GetAccountIncomes\GetAccountIncomesCommand;
use App\UseCase\GetAccountIncomes\GetAccountIncomesUseCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Symfony\Component\HttpFoundation\Request;
use Test\Unit\Shared\Infrastructure\Controller\AuthorizedControllerTest;

class GetIncomesControllerTest extends AuthorizedControllerTest
{
    use ProphecyTrait;
    private $getAccountIncomesUseCase;

    public function setUp(): void
    {
        parent::setUp();
        $this->getAccountIncomesUseCase = $this->prophesize(GetAccountIncomesUseCase::class);
    }

    public function test_fails_when_unauthorized()
    {
        $request = new Request();
        $controller = $this->getController();

        $this->expectException(InvalidCredentialsException::class);

        $controller($request);
    }

    public function test_returns_usecase_result()
    {
        $request = new Request();
        $request->headers->set('Authorization', 'Bearer '.self::TOKEN);
        $userIncome = new Income(
            new Id(123),
            $this->user->accountId(),
            new Money(100, "EUR"),
            "Capsa 12 Moixa Amber Ale",
            new \DateTime('2023-05-07'),
        );
        $this->getAccountIncomesUseCase->__invoke(Argument::type(GetAccountIncomesCommand::class))
            ->shouldBeCalled()
            ->willReturn(new AccountIncomes([$userIncome]));
        $controller = $this->getController();

        $result = $controller($request);

        $this->assertInstanceOf(ApiResponse::class, $result);
        $decodedContent = json_decode($result->getContent(), true);
        $this->assertCount(1, $decodedContent);
        $this->assertEquals(123, $decodedContent[0]['id']);

    }
    private function getController(): GetIncomesController
    {
        return new GetIncomesController(
            $this->tokenDecoder->reveal(),
            $this->userRepository->reveal(),
            $this->getAccountIncomesUseCase->reveal(),
        );
    }
}
