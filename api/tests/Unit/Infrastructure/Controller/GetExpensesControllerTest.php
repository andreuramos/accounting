<?php

namespace Test\Unit\Infrastructure\Controller;

use App\Application\UseCase\GetAccountExpenses\AccountExpenses;
use App\Application\UseCase\GetAccountExpenses\GetAccountExpensesCommand;
use App\Application\UseCase\GetAccountExpenses\GetAccountExpensesUseCase;
use App\Domain\Entities\Expense;
use App\Domain\Exception\InvalidCredentialsException;
use App\Domain\ValueObject\Id;
use App\Domain\ValueObject\Money;
use App\Infrastructure\Controller\GetExpensesController;
use Prophecy\PhpUnit\ProphecyTrait;
use Symfony\Component\HttpFoundation\Request;

class GetExpensesControllerTest extends AuthorizedControllerTest
{
    use ProphecyTrait;

    private $useCase;

    public function setUp(): void
    {
        parent::setUp();
        $this->useCase = $this->prophesize(GetAccountExpensesUseCase::class);
    }

    public function test_fails_if_no_authorized()
    {
        $controller = $this->getController();
        $request = new Request();

        $this->expectException(InvalidCredentialsException::class);

        $controller($request);
    }

    public function test_returns_usecase_result_when_authorized()
    {
        $request = new Request();
        $request->headers->set('Authorization', 'Bearer ' . self::TOKEN);
        $command = new GetAccountExpensesCommand($this->user->accountId());
        $userExpense = new Expense(
            new Id(null),
            $this->user->accountId(),
            new Money(100, "EUR"),
            "",
            new \DateTime()
        );
        $this->useCase->__invoke($command)
            ->shouldBeCalled()
            ->willReturn(new AccountExpenses([$userExpense]));
        $controller = $this->getController();

        $response = $controller($request);

        $this->assertEquals(200, $response->getStatusCode());
        $result = json_decode($response->getContent(), true);
        $this->assertcount(1, $result);
    }

    private function getController(): GetExpensesController
    {
        return new GetExpensesController(
            $this->tokenDecoder->reveal(),
            $this->userRepository->reveal(),
            $this->useCase->reveal(),
        );
    }
}
