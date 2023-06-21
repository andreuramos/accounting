<?php

namespace Test\Unit\Transaction\Infrastructure\Controller;

use App\Shared\Domain\ValueObject\Id;
use App\Transaction\Application\Command\GetAccountExpensesCommand;
use App\Transaction\Application\Result\UserExpenses;
use App\Transaction\Application\UseCase\GetUserExpensesUseCase;
use App\Transaction\Domain\Entity\Expense;
use App\Transaction\Domain\ValueObject\Money;
use App\Transaction\Infrastructure\Controller\GetExpensesController;
use App\User\Domain\Exception\InvalidCredentialsException;
use Prophecy\PhpUnit\ProphecyTrait;
use Symfony\Component\HttpFoundation\Request;
use Test\Unit\Shared\Infrastructure\Controller\AuthorizedControllerTest;

class GetExpensesControllerTest extends AuthorizedControllerTest
{
    use ProphecyTrait;

    private $useCase;

    public function setUp(): void
    {
        parent::setUp();
        $this->useCase = $this->prophesize(GetUserExpensesUseCase::class);
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
            $this->user->id(),
            $this->user->accountId(),
            new Money(100, "EUR"),
            "",
            new \DateTime()
        );
        $this->useCase->__invoke($command)
            ->shouldBeCalled()
            ->willReturn(new UserExpenses([$userExpense]));
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
