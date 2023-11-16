<?php

namespace App\Domain\Service;

use App\Domain\Entities\TaxAgency303Form;

class TaxAgency303FormPage1Renderer
{
    public function __invoke(TaxAgency303Form $declaration): string
    {
        return '<T30301000> ';
    }
}
