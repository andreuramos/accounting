<?php

namespace Test\Unit\User\Infrastructure\Controller;

use App\User\Application\Command\RegisterUserCommand;
use App\User\Domain\Service\UserRegisterer;
use App\User\Infrastructure\Controller\RegisterUserController;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Exception\MissingMandatoryParametersException;

class RegisterUserControllerTest extends TestCase
{
    use ProphecyTrait;

    private $userRegisterer;

    public function setUp(): void
    {
        parent::setUp();
        $this->userRegisterer = $this->prophesize(UserRegisterer::class);
    }

    public function test_it_returns_200_response(): void
    {
        $email = 'email@email.com';
        $command = new RegisterUserCommand($email);
        $this->userRegisterer->execute($command)
            ->shouldBeCalled();
        $controller = $this->buildController();
        $request = $this->prophesize(Request::class);
        $requestBody = [
            'email' => $email
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

        $this->expectException(MissingMandatoryParametersException::class);
        $controller($request->reveal());
    }


    private function buildController(): RegisterUserController
    {
        return new RegisterUserController(
            $this->userRegisterer->reveal()
        );
    }
}
