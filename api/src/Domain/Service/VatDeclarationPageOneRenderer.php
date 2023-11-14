<?php

namespace App\Domain\Service;

use App\Domain\Entities\VatDeclaration;

class VatDeclarationPageOneRenderer
{
    public function __invoke(VatDeclaration $declaration): string
    {
        return '<T30301000> ';
    }
}
