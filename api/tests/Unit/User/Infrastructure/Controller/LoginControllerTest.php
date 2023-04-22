<?php

namespace Test\Unit\User\Infrastructure\Controller;

use App\Shared\Domain\Exception\MissingMandatoryParameterException;
use App\User\Application\Command\LoginCommand;
use App\User\Application\Result\LoginResult;
use App\User\Application\UseCase\LoginUseCase;
use App\User\Domain\Exception\InvalidCredentialsException;
use App\User\Domain\ValueObject\Email;
use App\User\Infrastructure\Controller\LoginController;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Symfony\Component\HttpFoundation\Request;

class LoginControllerTest extends TestCase
{
    use ProphecyTrait;
    private $loginUseCase;

    public function setUp(): void
    {
        parent::setUp();
        $this->loginUseCase = $this->prophesize(LoginUseCase::class);
    }

    public function test_throws_exception_if_missing_parameters(): void
    {
        $request = $this->prophesize(Request::class);
        $request->getContent()->willReturn(json_encode([]));
        $controller = $this->getController();

        $this->expectException(MissingMandatoryParameterException::class);

        $controller($request->reveal());
    }

    public function test_returns_200_when_credentials_are_right()
    {
        $request = $this->prophesize(Request::class);
        $request->getContent()->willReturn(json_encode([
            "email" => "some@email.com",
            "password" => "mypass",
        ], JSON_THROW_ON_ERROR));
        $command = new LoginCommand(new Email("some@email.com"), "mypass");
        $this->loginUseCase->__invoke($command)->shouldBeCalled()
            ->willReturn(new LoginResult("", ""));

        $controller = $this->getController();
        $result = $controller($request->reveal());

        $this->assertEquals(200, $result->getStatusCode());
        $decodedContent = json_decode($result->getContent(), true);
        $this->assertArrayHasKey("token", $decodedContent);
        $this->assertArrayHasKey("refresh", $decodedContent);
    }

    public function test_returns_401_when_credentials_are_wrong()
    {
        $request = $this->prophesize(Request::class);
        $request->getContent()->willReturn(json_encode([
            "email" => "some@email.com",
            "password" => "mypass",
        ], JSON_THROW_ON_ERROR));
        $command = new LoginCommand(new Email("some@email.com"), "mypass");
        $this->loginUseCase->__invoke($command)->willThrow(new InvalidCredentialsException());

        $controller = $this->getController();
        $result = $controller($request->reveal());

        $this->assertEquals(401, $result->getStatusCode());
    }

    private function getController(): LoginController
    {
        return new LoginController(
            $this->loginUseCase->reveal()
        );
    }
}
