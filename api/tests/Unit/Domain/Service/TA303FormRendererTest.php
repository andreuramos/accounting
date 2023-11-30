<?php

namespace Test\Unit\Domain\Service;

use App\Domain\Service\TA303FormRenderer;
use App\Domain\ValueObject\AccruedTax;
use App\Domain\ValueObject\DeductibleTax;
use App\Domain\ValueObject\DeclarationPeriod;
use PHPUnit\Framework\TestCase;

class TA303FormRendererTest extends TestCase
{
    public function test_first_quarter_with_negative_result(): void
    {
        $expected_output = file_get_contents(__DIR__ . '/3032022T1');
        $service = new TA303FormRenderer();
        
        $output = $service(
            2022,
            DeclarationPeriod::QUARTER(1),
            "59519037M",
            "ROSSO ACEITUNO JULIAN",
            new AccruedTax(741_45, 21_00, 155_71),
            new DeductibleTax(4527_29, 950_73), 
            'ES9701280581210100059701',
        );
        
        $this->assertEquals(
            $expected_output, 
            $output,
        );
    }
}