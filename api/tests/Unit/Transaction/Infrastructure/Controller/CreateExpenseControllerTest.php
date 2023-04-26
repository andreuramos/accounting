<?php

namespace Test\Unit\Transaction\Infrastructure\Controller;

use App\Shared\Domain\Exception\MissingMandatoryParameterException;
use App\Shared\Domain\ValueObject\Id;
use App\Shared\Infrastructure\ApiResponse;
use App\Transaction\Application\Command\CreateExpenseCommand;
use App\Transaction\Application\UseCase\CreateExpenseUseCase;
use App\Transaction\Infrastructure\Controller\CreateExpenseController;
use App\User\Domain\Entity\User;
use App\User\Domain\Exception\InvalidCredentialsException;
use App\User\Domain\Model\UserRepositoryInterface;
use App\User\Domain\ValueObject\Email;
use App\User\Infrastructure\Auth\JWTDecoder;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Symfony\Component\HttpFoundation\Request;

class CreateExpenseControllerTest extends TestCase
{
    use ProphecyTrait;

    private const TOKEN = 'my.jwt.token';
    private $tokenDecoder;
    private $userRepository;
    private $createExpenseUseCase;
    private User $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->tokenDecoder = $this->prophesize(JWTDecoder::class);
        $this->userRepository = $this->prophesize(UserRepositoryInterface::class);
        $this->createExpenseUseCase = $this->prophesize(CreateExpenseUseCase::class);

        $this->user = new User(new Id(1), new Email("any@email.com"), "pass");
        $this->tokenDecoder->__invoke(self::TOKEN)->willReturn([
            'user' => $this->user->email()->toString(),
            'expiration' => date_create()->getTimestamp() + 1000,
        ]);
        $this->userRepository->getByEmail($this->user->email())->willReturn($this->user);
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
