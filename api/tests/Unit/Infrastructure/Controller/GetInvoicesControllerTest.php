<?php

namespace Test\Unit\Infrastructure\Controller;

use App\Domain\Exception\InvalidCredentialsException;
use App\Infrastructure\Controller\GetInvoicesController;
use Prophecy\PhpUnit\ProphecyTrait;
use Symfony\Component\HttpFoundation\Request;

class GetInvoicesControllerTest extends AuthorizedControllerTest
{
    use ProphecyTrait;
    private $usecase;
    
    public function setUp(): void
    {
        parent::setUp();
        $this->usecase = null;
    }
    
    public function test_fails_when_unauthorized(): void
    {
        $request = new Request();
        $controller = $this->getController();
        
        $this->expectException(InvalidCredentialsException::class);
        
        $controller($request);
    }

    private function getController(): GetInvoicesController
    {
        return new GetInvoicesController(
            $this->tokenDecoder->reveal(),
            $this->userRepository->reveal(),
        );
    }
}