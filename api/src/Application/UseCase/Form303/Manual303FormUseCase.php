<?php

namespace App\Application\UseCase\Form303;

use App\Domain\Entities\TaxAgency303Form;
use App\Domain\Service\TA303FormRenderer;
use App\Domain\ValueObject\AccruedTax;
use App\Domain\ValueObject\DeclarationPeriod;
use App\Domain\ValueObject\DeductibleTax;
use App\Domain\ValueObject\Money;

class Manual303FormUseCase
{
    public function __construct(
        private readonly TA303FormRenderer $renderer,
    ) {
    }

    public function __invoke(Manual303FormCommand $request): Importable303Form
    {
        $accrued = new AccruedTax(
            $request->accrued_base,
            21_00,
            $request->accrued_tax,
        );
        $deductible = new DeductibleTax(
            $request->deductible_base,
            $request->deductible_tax,
        );

        $form = new TaxAgency303Form(
            $request->tax_number,
            $request->tax_name,
            $request->year,
            DeclarationPeriod::QUARTER($request->quarter),
            $accrued,
            $deductible,
            $request->iban,
            new Money($request->pending_from_previous_periods),
        );

        $importableFile = ($this->renderer)($form);

        return new Importable303Form($importableFile);
    }
}
