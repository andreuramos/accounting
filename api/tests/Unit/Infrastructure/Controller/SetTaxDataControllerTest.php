<?php

namespace Test\Unit\Infrastructure\Controller;

use App\Application\UseCase\SetUserTaxData\SetUserTaxDataCommand;
use App\Application\UseCase\SetUserTaxData\SetUserTaxDataUseCase;
use App\Domain\Exception\InvalidCredentialsException;
use App\Domain\Exception\MissingMandatoryParameterException;
use App\Infrastructure\ApiResponse;
use App\Infrastructure\Controller\SetUserTaxDataController;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Symfony\Component\HttpFoundation\Request;

class SetTaxDataControllerTest extends AuthorizedControllerTest
{
    use ProphecyTrait;

    private $setTaxDataUseCase;

    public function setUp(): void
    {
        parent::setUp();
        $this->setTaxDataUseCase = $this->prophesize(SetUserTaxDataUseCase::class);
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
        $request = $this->buildAuthorizedRequest([
            'tax_number' => "B07656565",
            'tax_address_street' => "Andreu Jaume Nadal 29",
            'tax_address_zip_code' => "07013",
        ]);
        $controller = $this->buildController();

        $this->expectException(MissingMandatoryParameterException::class);

        $controller($request);
    }

    public function test_usecase_is_called()
    {
        $request = $this->buildAuthorizedRequest([
            'tax_name' => "Moixa Brewing SL",
            'tax_number' => "B07656565",
            'tax_address_street' => "Andreu Jaume Nadal 29",
            'tax_address_zip_code' => "07013",
        ]);
        $controller = $this->buildController();
        $this->setTaxDataUseCase->__invoke(Argument::type(SetUserTaxDataCommand::class))
            ->shouldBeCalled();

        $response = $controller($request);

        $this->assertInstanceOf(ApiResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    private function buildController(): SetUserTaxDataController
    {
        return new SetUserTaxDataController(
            $this->tokenDecoder->reveal(),
            $this->userRepository->reveal(),
            $this->setTaxDataUseCase->reveal(),
        );
    }
}
