<?php

namespace Test\Unit\User\Infrastructure\Controller;

use App\Shared\Domain\Exception\MissingMandatoryParameterException;
use App\User\Application\Command\RefreshTokensCommand;
use App\User\Application\Result\LoginResult;
use App\User\Application\UseCase\RefreshTokensUseCase;
use App\User\Infrastructure\Controller\RefreshTokenController;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Symfony\Component\HttpFoundation\Request;

class RefreshTokenControllerTest extends TestCase
{
    use ProphecyTrait;

    private $useCase;

    public function setUp(): void
    {
        parent::setUp();
        $this->useCase = $this->prophesize(RefreshTokensUseCase::class);
    }

    public function test_no_token_throws_exception()
    {
        $request = $this->prophesize(Request::class);
        $request->getContent()->willReturn(json_encode([]));
        $controller = new RefreshTokenController($this->useCase->reveal());

        $this->expectException(MissingMandatoryParameterException::class);

        $controller->__invoke($request->reveal());
    }

    public function test_valid_request_returns_tokens()
    {
        $request = $this->prophesize(Request::class);
        $request->getContent()->willReturn(json_encode([
            'refresh_token' => 'im.very.valid'
        ], JSON_THROW_ON_ERROR));
        $command = new RefreshTokensCommand("im.very.valid");
        $result = new LoginResult("new_auth", "new_refresh");
        $this->useCase->__invoke($command)->willReturn($result);
        $controller = new RefreshTokenController($this->useCase->reveal());

        $response = $controller->__invoke($request->reveal());

        $this->assertEquals(200, $response->getStatusCode());
        $content = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('token', $content);
        $this->assertArrayHasKey('refresh', $content);
    }
}
