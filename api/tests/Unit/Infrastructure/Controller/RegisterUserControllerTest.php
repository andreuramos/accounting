<?php

namespace Test\Unit\Infrastructure\Controller;

use App\Application\UseCase\RegisterUser\RegisterUserCommand;
use App\Application\UseCase\RegisterUser\RegisterUserUseCase;
use App\Domain\Exception\MissingMandatoryParameterException;
use App\Infrastructure\Controller\RegisterUserController;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Symfony\Component\HttpFoundation\Request;

class RegisterUserControllerTest extends TestCase
{
    use ProphecyTrait;

    private $registerUserUseCase;

    public function setUp(): void
    {
        parent::setUp();
        $this->registerUserUseCase = $this->prophesize(RegisterUserUseCase::class);
    }

    public function test_it_returns_200_response(): void
    {
        $email = 'email@email.com';
        $password = 'desiredPassw0rd';
        $command = new RegisterUserCommand($email, $password);
        $this->registerUserUseCase->__invoke($command)
            ->shouldBeCalled();
        $controller = $this->buildController();
        $request = $this->prophesize(Request::class);
        $requestBody = [
            'email' => $email,
            'password' => $password,
        ];
        $request->getContent()
            ->willReturn(json_encode($requestBody));

        $response = $controller($request->reveal());

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_it_returns_400_when_no_email(): void
    {
        $request = $this->prophesize(Request::class);
        $request->getContent()
            ->willReturn(json_encode([]));
        $controller = $this->buildController();

        $this->expectException(MissingMandatoryParameterException::class);
        $controller($request->reveal());
    }


    private function buildController(): RegisterUserController
    {
        return new RegisterUserController(
            $this->registerUserUseCase->reveal()
        );
    }
}
