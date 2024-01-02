<?php

namespace Test\Unit\Infrastructure\Controller;

use App\Application\UseCase\Form303\Importable303Form;
use App\Application\UseCase\Form303\Manual303FormCommand;
use App\Application\UseCase\Form303\Manual303FormUseCase;
use App\Domain\Exception\MissingMandatoryParameterException;
use App\Infrastructure\Controller\Manual303FormController;
use App\Infrastructure\FileApiResponse;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;

class Manual303FormControllerTest extends BaseControllerTest
{
    use ProphecyTrait;
    private $useCase;
    
    public function setUp(): void
    {
        $this->useCase = $this->prophesize(Manual303FormUseCase::class);
    }

    public function test_fails_if_missing_parameters(): void
    {
        $request = $this->buildRequest([
            //"tax_name" => "ROSSO ACEITUNO JULIAN",
            "tax_id" => "59519037M",
            "year" => 2022,
            "quarter" => 2,
            "accrued_base" => 741_45,
            "accrued_tax" => 155_71,
            "deductible_base" => 4527_29,
            "deductible_tax" => 950_73,
            "iban" => "ES9701280581210100059701",
        ]);
        $controller = $this->getController();

        $this->expectException(MissingMandatoryParameterException::class);

        $controller($request);
    }
    
    public function test_usecase_is_called_and_response_is_a_file(): void
    {
        $request = $this->buildRequest([
            "tax_name" => "ROSSO ACEITUNO JULIAN",
            "tax_id" => "59519037M",
            "year" => 2022,
            "quarter" => 2,
            "accrued_base" => 741_45,
            "accrued_tax" => 155_71,
            "deductible_base" => 4527_29,
            "deductible_tax" => 950_73,
            "iban" => "ES9701280581210100059701",
            "pending_from_other_periods" => 0,
        ]);
        $controller = $this->getController();
        $useCaseResponse = new Importable303Form("<303>PAYTAXES</303>");
        $this->useCase->__invoke(Argument::type(Manual303FormCommand::class))
            ->shouldBeCalled()
            ->willReturn($useCaseResponse);
        
        $response = $controller($request);
        
        self::assertInstanceOf(FileApiResponse::class, $response);
        self::assertEquals("<303>PAYTAXES</303>", $response->getContent());
    }

    private function getController(): Manual303FormController
    {
        return new Manual303FormController(
            $this->useCase->reveal(),
        );
    }
}