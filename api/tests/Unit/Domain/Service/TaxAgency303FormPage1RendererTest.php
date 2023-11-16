<?php

namespace Test\Unit\Domain\Service;

use App\Domain\Entities\TaxAgency303Form;
use App\Domain\Service\TaxAgency303FormPage1Renderer;
use App\Domain\ValueObject\DeclarationPeriod;
use PHPUnit\Framework\TestCase;

class TaxAgency303FormPage1RendererTest extends TestCase
{
    public function test_renders_empty_page_one_for_not_last_period(): void
    {
        $expected_page_one_section = file_get_contents(__DIR__ . "/30301");
        $declaration = new TaxAgency303Form(
            '12345678Z',
            'Cervesa Moixa SLU',
            2023,
            DeclarationPeriod::QUARTER(2)
        );
        $pageOneRenderer = new TaxAgency303FormPage1Renderer();

        $output = $pageOneRenderer($declaration);

        $this->assertEquals($expected_page_one_section, $output);
    }
}
