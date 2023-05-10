<?php

namespace Test\Unit\Tax\Infrastructure\Controller;

use App\Shared\Domain\Exception\MissingMandatoryParameterException;
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
        $request = $this->buildAuthorizedRequest([
            'tax_number' => "B07656565",
            'tax_address_street' => "Andreu Jaume Nadal 29",
            'tax_address_zip_code' => "07013",
            'tax_address_region' => "Illes Balears",
        ]);
        $controller = $this->buildController();

        $this->expectException(MissingMandatoryParameterException::class);

        $controller($request);
    }

    private function buildController(): SetTaxDataController
    {
        return new SetTaxDataController(
            $this->tokenDecoder->reveal(),
            $this->userRepository->reveal()
        );
    }
}
