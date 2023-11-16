<?php

namespace App\Domain\Service;

use App\Domain\Entities\TaxAgency303Form;

class TaxAgency303FormRenderer
{
    private const FORM_ID = 303;

    public function __construct(
        private readonly TaxAgency303Page0Renderer $envelopeRenderer
    ) {
    }

    public function __invoke(TaxAgency303Form $vatDeclaration): string
    {
        return ($this->envelopeRenderer)(
            self::FORM_ID,
            $vatDeclaration->year,
            $vatDeclaration->period,
            '',
        );
    }
}
