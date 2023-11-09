<?php

namespace App\Domain\Service;

use App\Domain\Entities\VatDeclaration;

class VatDeclarationRenderer
{
    private const FORM_ID = 303;

    public function __construct(
        private readonly DeclarationEnvelopeRenderer $envelopeRenderer
    ) {
    }

    public function __invoke(VatDeclaration $vatDeclaration): string
    {
        return ($this->envelopeRenderer)(
            self::FORM_ID,
            $vatDeclaration->year,
            $vatDeclaration->period,
            '',
        );
    }
}
