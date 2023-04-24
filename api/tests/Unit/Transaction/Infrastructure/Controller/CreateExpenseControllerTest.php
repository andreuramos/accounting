<?php

namespace Test\Unit\Transaction\Infrastructure\Controller;

use App\Transaction\Infrastructure\Controller\CreateExpenseController;
use App\User\Application\Auth\AuthTokenDecoderInterface;
use App\User\Domain\Exception\InvalidCredentialsException;
use App\User\Domain\Model\UserRepositoryInterface;
use App\User\Infrastructure\Auth\JWTDecoder;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Symfony\Component\HttpFoundation\Request;

class CreateExpenseControllerTest extends TestCase
{
    use ProphecyTrait;

    private $tokenDecoder;
    private $userRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->tokenDecoder = $this->prophesize(JWTDecoder::class);
        $this->userRepository = $this->prophesize(UserRepositoryInterface::class);
    }

    public function test_unauthorized_request_fails()
    {
        $request = new Request();
        $controller = $this->getController();

        $this->expectException(InvalidCredentialsException::class);

        $controller($request);
    }

    private function getController(): CreateExpenseController
    {
        return new CreateExpenseController(
            $this->tokenDecoder->reveal(),
            $this->userRepository->reveal()
        );
    }
}
