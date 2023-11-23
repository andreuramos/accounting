<?php

namespace Test\Unit\Domain\Service;

use App\Domain\Service\TA303FormRenderer;
use App\Domain\ValueObject\DeclarationPeriod;
use PHPUnit\Framework\TestCase;

class TA303FormRendererTest extends TestCase
{
    public function test_opening_tag(): void
    {
        $expected_output = file_get_contents(__DIR__ . '/3032022T1');
        $service = new TA303FormRenderer();
        
        $output = $service(
            2022, 
            DeclarationPeriod::QUARTER(1),
            "59519037M",
            "ROSSO ACEITUNO JULIAN",
            new TopLine(741_45,21,155_71),
            new BottomLine(4527_29, 950_73),
        );
        
        $this->assertEquals(
            substr($expected_output, 0 ,17), 
            substr($output, 0, 17),
        );
    }

    public function test_aux_tag(): void
    {
        $expected_output = file_get_contents(__DIR__ . '/3032022T1');
        $service = new TA303FormRenderer();

        $output = $service(
            2022,
            DeclarationPeriod::QUARTER(1),
            "59519037M",
            "ROSSO ACEITUNO JULIAN",
            new TopLine(741_45,21,155_71),
            new BottomLine(4527_29, 950_73),
        );

        $this->assertEquals(
            substr($expected_output, 17 ,311),
            substr($output, 17, 311),
        );
    }
    
    public function test_identification_data(): void
    {
        $expected_output = file_get_contents(__DIR__ . '/3032022T1');
        $service = new TA303FormRenderer();

        $output = $service(
            2022,
            DeclarationPeriod::QUARTER(1),
            "59519037M",
            "ROSSO ACEITUNO JULIAN",
            new TopLine(741_45,21,155_71),
            new BottomLine(4527_29, 950_73),
        );

        $this->assertEquals(
            substr($expected_output, 328 ,129),
            substr($output, 328, 129),
        );    
    }
    
    public function test_first_declaration(): void
    {
        $this->markTestIncomplete("wip");
        $expected_output = file_get_contents(__DIR__ . '/3032022T1');
        $service = new TA303FormRenderer();
        
        $output = $service(
            2022, 
            DeclarationPeriod::QUARTER(1),
            "59519037M",
            "ROSSO ACEITUNO JULIAN",
            new TopLine(741_45,21,155_71),
            new BottomLine(4527_29, 950_73),
        );

        $this->assertEquals($expected_output, $output);
    }
}