<?php

namespace Test\Unit\Transaction\Infrastructure\Controller;

use App\Transaction\Infrastructure\Controller\GetExpensesController;
use App\User\Application\Auth\AuthTokenDecoderInterface;
use App\User\Domain\Exception\InvalidCredentialsException;
use App\User\Domain\Model\UserRepositoryInterface;
use App\User\Infrastructure\Auth\JWTDecoder;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Symfony\Component\HttpFoundation\Request;

class GetExpensesControllerTest extends TestCase
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

    public function test_fails_if_no_authorized()
    {
        $controller = $this->getController();
        $request = new Request();

        $this->expectException(InvalidCredentialsException::class);

        $controller($request);
    }

    /**
     * @return void
     */
    private function getController(): GetExpensesController
    {
        return new GetExpensesController(
            $this->tokenDecoder->reveal(),
            $this->userRepository->reveal(),
        );
    }
}
