<?php

namespace Test\Unit\Domain\Service;

use App\Domain\Service\TA303FormRenderer;
use App\Domain\ValueObject\DeclarationPeriod;
use App\Domain\ValueObject\AccruedTax;
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
            new AccruedTax(741_45, 21, 155_71),
            new BottomLine(4527_29, 950_73), 
            'ES9701280581210100059701',
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
            new AccruedTax(741_45, 21, 155_71),
            new BottomLine(4527_29, 950_73),
            'ES9701280581210100059701',
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
            new AccruedTax(741_45, 21, 155_71),
            new BottomLine(4527_29, 950_73),
            'ES9701280581210100059701',
        );

        $this->assertEquals(
            substr($expected_output, 328 ,129),
            substr($output, 328, 129),
        );
    }
    
    public function test_accrued_tax_data(): void
    {
        $expected_output = file_get_contents(__DIR__ . '/3032022T1');
        $service = new TA303FormRenderer();

        $output = $service(
            2022,
            DeclarationPeriod::QUARTER(1),
            "59519037M",
            "ROSSO ACEITUNO JULIAN",
            new AccruedTax(741_45, 21_00, 155_71),
            new BottomLine(4527_29, 950_73),
            'ES9701280581210100059701',
        );

        $this->assertEquals(
            substr($expected_output, 457 ,387),
            substr($output, 457, 387),
        );
    }
    
    public function test_deductible_tax_data(): void
    {
        $expected_output = file_get_contents(__DIR__ . '/3032022T1');
        $service = new TA303FormRenderer();

        $output = $service(
            2022,
            DeclarationPeriod::QUARTER(1),
            "59519037M",
            "ROSSO ACEITUNO JULIAN",
            new AccruedTax(741_45, 21_00, 155_71),
            new BottomLine(4527_29, 950_73),
            'ES9701280581210100059701',
        );

        $this->assertEquals(
            substr($expected_output, 844 ,323),
            substr($output, 844, 323),
        );
    }
    
    public function test_agency_reserved_space_and_closing_tag(): void
    {
        $expected_output = file_get_contents(__DIR__ . '/3032022T1');
        $service = new TA303FormRenderer();

        $output = $service(
            2022,
            DeclarationPeriod::QUARTER(1),
            "59519037M",
            "ROSSO ACEITUNO JULIAN",
            new AccruedTax(741_45, 21_00, 155_71),
            new BottomLine(4527_29, 950_73),
            'ES9701280581210100059701',
        );

        $this->assertEquals(
            substr($expected_output, 1167 ,625),
            substr($output, 1167, 625),
        );
    }    
    
    public function test_page_tag_and_additional_info(): void
    {
        $expected_output = file_get_contents(__DIR__ . '/3032022T1');
        $service = new TA303FormRenderer();

        $output = $service(
            2022,
            DeclarationPeriod::QUARTER(1),
            "59519037M",
            "ROSSO ACEITUNO JULIAN",
            new AccruedTax(741_45, 21_00, 155_71),
            new BottomLine(4527_29, 950_73),
            'ES9701280581210100059701',
        );

        $this->assertEquals(
            substr($expected_output, 1792 ,198),
            substr($output, 1792, 198),
        );
    }
    
    public function test_result(): void
    {
        $expected_output = file_get_contents(__DIR__ . '/3032022T1');
        $service = new TA303FormRenderer();

        $output = $service(
            2022,
            DeclarationPeriod::QUARTER(1),
            "59519037M",
            "ROSSO ACEITUNO JULIAN",
            new AccruedTax(741_45, 21_00, 155_71),
            new BottomLine(4527_29, 950_73),
            'ES9701280581210100059701',
        );

        $this->assertEquals(
            substr($expected_output, 1990 ,175),
            substr($output, 1990, 175),
        );
    }
    
    public function test_other_data_and_end_tag(): void
    {
        $expected_output = file_get_contents(__DIR__ . '/3032022T1');
        $service = new TA303FormRenderer();

        $output = $service(
            2022,
            DeclarationPeriod::QUARTER(1),
            "59519037M",
            "ROSSO ACEITUNO JULIAN",
            new AccruedTax(741_45, 21_00, 155_71),
            new BottomLine(4527_29, 950_73),
            'ES9701280581210100059701',
        );

        $this->assertEquals(
            substr($expected_output, 2165 ,845),
            substr($output, 2165, 845),
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
            new AccruedTax(741_45, 21, 155_71),
            new BottomLine(4527_29, 950_73),
            'ES9701280581210100059701',
        );

        $this->assertEquals($expected_output, $output);
    }
}