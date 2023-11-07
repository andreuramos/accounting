<?php

namespace App\Domain\Service;

use App\Domain\Entities\VatDeclaration;

class VatDeclarationRenderer
{
    public function __construct(
        private readonly DeclarationEnvelopeRenderer $envelopeRenderer
    ) {
    }

    public function __invoke(VatDeclaration $vatDeclaration): string
    {
        return ($this->envelopeRenderer)(
            $vatDeclaration->year,
            $vatDeclaration->period,
            '',
        );
    }
}
