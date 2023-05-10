<?php

namespace Test\Unit\Tax\Infrastructure\Controller;

use App\Tax\Infrastructure\Controller\SetTaxDataController;
use App\User\Domain\Exception\InvalidCredentialsException;
use Prophecy\PhpUnit\ProphecyTrait;
use Symfony\Component\HttpFoundation\Request;
use Test\Unit\Shared\Infrastructure\Controller\AuthorizedControllerTest;

class SetTaxDataControllerTest extends AuthorizedControllerTest
{
    use ProphecyTrait;

    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_unauthroized_fails()
    {
        $request = new Request();
        $controller = $this->buildController();

        $this->expectException(InvalidCredentialsException::class);

        $controller($request);
    }

    public function test_missing_tax_name_fails()
    {
        $request = $this->buildAuthorizedRequest([]);
    }

    private function buildController(): SetTaxDataController
    {
        return new SetTaxDataController(
            $this->tokenDecoder->reveal(),
            $this->userRepository->reveal()
        );
    }
}
