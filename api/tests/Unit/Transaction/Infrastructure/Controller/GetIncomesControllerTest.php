<?php

namespace Test\Unit\Transaction\Infrastructure\Controller;

use App\Transaction\Infrastructure\Controller\GetIncomesController;
use App\User\Domain\Exception\InvalidCredentialsException;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Symfony\Component\HttpFoundation\Request;
use Test\Unit\Shared\Infrastructure\Controller\AuthorizedControllerTest;

class GetIncomesControllerTest extends AuthorizedControllerTest
{
    use ProphecyTrait;

    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_fails_when_unauthorized()
    {
        $request = new Request();
        $controller = $this->getController();

        $this->expectException(InvalidCredentialsException::class);

        $controller($request);
    }

    /**
     * @return GetIncomesController
     */
    private function getController(): GetIncomesController
    {
        return new GetIncomesController(
            $this->tokenDecoder->reveal(),
            $this->userRepository->reveal()
        );
    }
}
