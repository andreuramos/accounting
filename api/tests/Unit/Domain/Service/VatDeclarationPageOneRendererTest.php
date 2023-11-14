<?php

namespace Test\Unit\Domain\Service;

use App\Domain\Entities\VatDeclaration;
use App\Domain\Service\VatDeclarationPageOneRenderer;
use App\Domain\ValueObject\DeclarationPeriod;
use PHPUnit\Framework\TestCase;

class VatDeclarationPageOneRendererTest extends TestCase
{
    public function test_renders_empty_page_one_for_not_last_period(): void
    {
        $expected_page_one_section = file_get_contents(__DIR__ . "/30301");
        $declaration = new VatDeclaration(
            '12345678Z',
            'Cervesa Moixa SLU',
            2023,
            DeclarationPeriod::QUARTER(2)
        );
        $pageOneRenderer = new VatDeclarationPageOneRenderer();

        $output = $pageOneRenderer($declaration);

        $this->assertEquals($expected_page_one_section, $output);
    }
}
