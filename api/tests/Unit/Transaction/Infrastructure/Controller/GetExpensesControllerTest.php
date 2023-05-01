<?php

namespace Test\Unit\Transaction\Infrastructure\Controller;

use App\Shared\Domain\ValueObject\Id;
use App\Transaction\Application\Command\GetUserExpensesCommand;
use App\Transaction\Application\Result\UserExpenses;
use App\Transaction\Application\UseCase\GetUserExpensesUseCase;
use App\Transaction\Domain\Entity\Expense;
use App\Transaction\Domain\ValueObject\Money;
use App\Transaction\Infrastructure\Controller\GetExpensesController;
use App\User\Application\Auth\AuthTokenDecoderInterface;
use App\User\Domain\Entity\User;
use App\User\Domain\Exception\InvalidCredentialsException;
use App\User\Domain\Model\UserRepositoryInterface;
use App\User\Domain\ValueObject\Email;
use App\User\Infrastructure\Auth\JWTDecoder;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Symfony\Component\HttpFoundation\Request;

class GetExpensesControllerTest extends TestCase
{
    use ProphecyTrait;

    private const TOKEN = 'my.jwt.token';
    private $tokenDecoder;
    private $userRepository;
    private $useCase;
    private User $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->tokenDecoder = $this->prophesize(JWTDecoder::class);
        $this->userRepository = $this->prophesize(UserRepositoryInterface::class);
        $this->useCase = $this->prophesize(GetUserExpensesUseCase::class);

        $this->user = new User(new Id(1), new Email("any@email.com"), "pass");
        $this->tokenDecoder->__invoke(self::TOKEN)->willReturn([
            'user' => $this->user->email()->toString(),
            'expiration' => date_create()->getTimestamp() + 1000,
        ]);
        $this->userRepository->getByEmail($this->user->email())->willReturn($this->user);
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
        $command = new GetUserExpensesCommand($this->user);
        $userExpense = new Expense(
            new Id(null),
            $this->user->id(),
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
