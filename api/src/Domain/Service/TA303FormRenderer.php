<?php

namespace App\Domain\Service;

use App\Domain\ValueObject\DeclarationPeriod;
use Test\Unit\Domain\Service\BottomLine;
use Test\Unit\Domain\Service\TopLine;

class TA303FormRenderer
{
    public function __construct()
    {
    }
    
    public function __invoke(
        int $year, 
        DeclarationPeriod $period,
        string $tax_id,
        string $tax_name,
        TopLine $top_line,
        BottomLine $bottom_line,
    ): string {
        return implode('',[
            "<T3030{$year}{$period}0000>",
            $this->generateAuxTag(),
        ]);
    }

    private function padding($width): string
    {
        return str_repeat(' ', $width);
    }

    private function generateAuxTag(): string
    {
        return implode('', [
            "<AUX>", 
            $this->padding(70), 
            'v2.0', 
            $this->padding(4), 
            '12345678Z', 
            $this->padding(213), 
            '</AUX>',
        ]);
    }
}