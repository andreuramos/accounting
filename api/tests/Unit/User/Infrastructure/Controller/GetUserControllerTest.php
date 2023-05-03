<?php

namespace Test\Unit\User\Infrastructure\Controller;

use App\User\Application\UseCase\GetUserUseCase;
use App\User\Domain\Exception\InvalidCredentialsException;
use App\User\Infrastructure\Controller\GetUserController;
use Prophecy\PhpUnit\ProphecyTrait;
use Symfony\Component\HttpFoundation\Request;
use Test\Unit\Shared\Infrastructure\Controller\AuthorizedControllerTest;

class GetUserControllerTest extends AuthorizedControllerTest
{
    use ProphecyTrait;

    private $getUserUseCase;

    public function setUp(): void
    {
        parent::setUp();
        $this->getUserUseCase = $this->prophesize(GetUserUseCase::class);
    }

    public function test_throws_exception_when_no_auth_header()
    {
        $request = new Request();
        $controller = $this->buildController();

        $this->expectException(InvalidCredentialsException::class);

        $controller($request);
    }

    public function test_returns_json_with_email()
    {
        $request = new Request();
        $request->headers->set('authorization', "Bearer ".self::TOKEN);

        $this->getUserUseCase->__invoke($this->user->email())->willReturn([
            'email' => "some@email.com"
        ]);
        $controller = $this->buildController();

        $result = $controller($request);

        $this->assertEquals("application/json", $result->headers->get('Content-Type'));
        $this->assertJson($result->getContent());
        $decodedResult = json_decode($result->getContent(), true);
        $this->assertArrayHasKey('email', $decodedResult);
    }

    private function buildController(): GetUserController
    {
        return new GetUserController(
            $this->tokenDecoder->reveal(),
            $this->userRepository->reveal(),
            $this->getUserUseCase->reveal()
        );
    }
}
