<?php

namespace Test\Unit\Application\UseCase;

use App\Application\UseCase\Form303\Importable303Form;
use App\Application\UseCase\Form303\Manual303FormCommand;
use App\Application\UseCase\Form303\Manual303FormUseCase;
use App\Domain\Entities\TaxAgency303Form;
use App\Domain\Service\TA303FormRenderer;
use App\Domain\ValueObject\AccruedTax;
use App\Domain\ValueObject\DeclarationPeriod;
use App\Domain\ValueObject\DeductibleTax;
use App\Domain\ValueObject\Money;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class Manual303FormUseCaseTest extends TestCase
{
    use ProphecyTrait;
    
    private $renderer;
    
    public function setUp(): void
    {
        $this->renderer = $this->prophesize(TA303FormRenderer::class);
    }

    public function test_service_is_called_with_form_instance(): void
    {
        $command = new Manual303FormCommand(
            "Rodolfo Langostino",
            "87654321Z",
            2023,
            1,
            200_00,
            42_00,
            100_00,
            21_00,
            "MYIBAN",
            0
        );
        $form = new TaxAgency303Form(
            "87654321Z",
            "Rodolfo Langostino",
            2023,
            DeclarationPeriod::QUARTER(1),
            new AccruedTax(200_00, 21_00, 42_00),
            new DeductibleTax(100_00, 21_00),
            "MYIBAN",
            new Money(0)
        );
        $this->renderer->__invoke($form)
            ->shouldBeCalled()
            ->willReturn("<303>PayYourTaxes</303>");
        $useCase = new Manual303FormUseCase($this->renderer->reveal());
        
        $response = $useCase($command);
        
        self::assertInstanceOf(Importable303Form::class, $response);
        self::assertEquals("<303>PayYourTaxes</303>", (string) $response);
    }
}