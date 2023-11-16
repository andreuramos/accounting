<?php

namespace App\Domain\Service;

use App\Domain\ValueObject\DeclarationPeriod;

class TaxAgency303Page0Renderer
{
    private const VERSION = 'v1.0';
    private const DEVELOPER_TAX_ID = '12345678Z';

    public function __invoke(int $form_id, int $year, DeclarationPeriod $period, string $content): string
    {
        $output = '<T' . $form_id . '0' . $year . $period . '0000>';
        $output .= '<AUX>';
        $output .= str_repeat(' ', 70);
        $output .= self::VERSION;
        $output .= str_repeat(' ', 97);
        $output .= self::DEVELOPER_TAX_ID;
        $output .= str_repeat(' ', 213);
        $output .= '</AUX>';
        $output .= $content;
        $output .= '</T' . $form_id . '0' . $year . $period . '0000>';

        return $output;
    }
}
