<?php

namespace Test\Unit\Infrastructure\Controller;

use App\Application\UseCase\GetInvoices\GetInvoicesCommand;
use App\Application\UseCase\GetInvoices\GetInvoicesUseCase;
use App\Domain\Exception\InvalidCredentialsException;
use App\Infrastructure\Controller\GetInvoicesController;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Symfony\Component\HttpFoundation\Request;

class GetInvoicesControllerTest extends AuthorizedControllerTest
{
    use ProphecyTrait;

    private $usecase;

    public function setUp(): void
    {
        parent::setUp();
        $this->usecase = $this->prophesize(GetInvoicesUseCase::class);
    }

    public function test_fails_when_unauthorized(): void
    {
        $request = new Request();
        $controller = $this->getController();

        $this->expectException(InvalidCredentialsException::class);

        $controller($request);
    }

    public function test_builds_command(): void
    {
        $request = new Request();
        $request->headers->set('Authorization', 'Bearer ' . self::TOKEN);

        $controller = $this->getController();
        $expectedCommand = new GetInvoicesCommand($this->user->accountId());
        $this->usecase->__invoke($expectedCommand)
            ->shouldBeCalled()
            ->willReturn([]);
        
        $response = $controller($request);
    }

    public function test_succeeds_with_no_results(): void
    {
        $request = new Request();
        $request->headers->set('Authorization', 'Bearer ' . self::TOKEN);
        $controller = $this->getController();
        $this->usecase->__invoke(Argument::type(GetInvoicesCommand::class))
            ->shouldBeCalled()
            ->willReturn([]);

        $response = $controller($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEmpty(json_decode($response->getContent(), true));
    }

    private function getController(): GetInvoicesController
    {
        return new GetInvoicesController(
            $this->tokenDecoder->reveal(),
            $this->userRepository->reveal(),
            $this->usecase->reveal(),
        );
    }
}