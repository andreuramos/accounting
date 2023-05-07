<?php

namespace Test\Unit\Transaction\Infrastructure\Controller;

use App\Shared\Domain\ValueObject\Id;
use App\Shared\Infrastructure\ApiResponse;
use App\Transaction\Application\Command\GetUserIncomesCommand;
use App\Transaction\Application\Result\UserIncomes;
use App\Transaction\Application\UseCase\GetUserIncomesUseCase;
use App\Transaction\Domain\Entity\Income;
use App\Transaction\Domain\ValueObject\Money;
use App\Transaction\Infrastructure\Controller\GetIncomesController;
use App\User\Domain\Exception\InvalidCredentialsException;
use Prophecy\PhpUnit\ProphecyTrait;
use Symfony\Component\HttpFoundation\Request;
use Test\Unit\Shared\Infrastructure\Controller\AuthorizedControllerTest;

class GetIncomesControllerTest extends AuthorizedControllerTest
{
    use ProphecyTrait;
    private $getUserIncomesUseCase;

    public function setUp(): void
    {
        parent::setUp();
        $this->getUserIncomesUseCase = $this->prophesize(GetUserIncomesUseCase::class);
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
        $command = new GetUserIncomesCommand($this->user);
        $userIncome = new Income(
            new Id(123),
            $this->user->id(),
            new Money(100, "EUR"),
            "Capsa 12 Moixa Amber Ale",
            new \DateTime('2023-05-07')
        );
        $this->getUserIncomesUseCase->__invoke($command)
            ->shouldBeCalled()
            ->willReturn(new UserIncomes([$userIncome]));
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
            $this->getUserIncomesUseCase->reveal(),
        );
    }
}
