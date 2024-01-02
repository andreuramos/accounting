<?php

namespace Test\Unit\Infrastructure\Controller;

use App\Domain\Exception\MissingMandatoryParameterException;
use App\Infrastructure\Controller\Manual303FormController;

class Manual303FormControllerTest extends BaseControllerTest
{
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

    private function getController(): Manual303FormController
    {
        return new Manual303FormController();
    }
}