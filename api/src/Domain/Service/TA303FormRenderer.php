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
        return implode('', [
            "<T3030{$year}{$period}0000>",
            $this->generateAuxTag(),
            $this->generatePage1(
                $tax_id,
                $tax_name,
                $year,
                $period,
            ),
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

    private function generatePage1(
        string $tax_id,
        string $tax_name,
        int $year,
        DeclarationPeriod $period,
    ): string {
        return implode('', [
            "<T30301000>",
            $this->padding(1),
            "C",
            $tax_id,
            $tax_name,
            $this->padding(80 - strlen($tax_name)),
            $year,
            $period,
            "2",
            "2",
            "3",
            "2",
            "2",
            "2",
            "2",
            "2",
            "2",
            $this->padding(8),
            " ",
            "2",
            "0",
            "0",
        ]);
    }
}
