<?php

namespace Test\Unit\User\Infrastructure\Controller;

use App\User\Application\Command\RegisterUserCommand;
use App\User\Infrastructure\Controller\RegisterUserController;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

class RegisterUserControllerTest extends TestCase
{
    use ProphecyTrait;

    private $bus;

    public function setUp(): void
    {
        parent::setUp();
        $this->bus = $this->prophesize(MessageBusInterface::class);
    }

    public function test_it_returns_200_response(): void
    {
        $email = 'email@email.com';
        $command = new RegisterUserCommand($email);
        $this->bus->dispatch($command)
            ->shouldBeCalled()
            ->willReturn(new Envelope($command));
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

    private function buildController(): RegisterUserController
    {
        return new RegisterUserController(
            $this->bus->reveal()
        );
    }
}
