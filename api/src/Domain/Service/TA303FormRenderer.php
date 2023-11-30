<?php

namespace App\Domain\Service;

use App\Domain\ValueObject\DeductibleTax;
use App\Domain\ValueObject\DeclarationPeriod;
use App\Domain\ValueObject\AccruedTax;

class TA303FormRenderer
{
    public function __invoke(
        int $year,
        DeclarationPeriod $period,
        string $tax_id,
        string $tax_name,
        AccruedTax $accruedTax,
        DeductibleTax $deductibleTax,
        string $IBAN,
    ): string {
        return implode('', [
            "<T3030{$year}{$period}0000>",
            $this->generateAuxTag(),
            $this->generatePage1(
                $tax_id,
                $tax_name,
                $year,
                $period,
                $accruedTax,
                $deductibleTax,
            ),
            $this->generatePage3(
                $accruedTax,
                $deductibleTax,
                $IBAN,
            ),
            "</T3030{$year}{$period}0000>"
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
        AccruedTax $accruedTax,
        DeductibleTax $deductibleTax,
    ): string {
        return implode('', [
            "<T30301000>",
            $this->generateIdentificationData(
                $tax_id,
                $tax_name,
                $year,
                $period,
            ),
            $this->generateAccruedTaxData($accruedTax),
            $this->generateDeductibleTaxData($deductibleTax, $accruedTax),
            $this->padding(613),
            "</T30301000>",
        ]);
    }

    private function generateIdentificationData(
        string $tax_id,
        string $tax_name,
        int $year,
        DeclarationPeriod $period,
    ): string {
        return implode('', [
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

    private function generateAccruedTaxData(
        AccruedTax $accruedTax,
    ): string {
        return implode('', [
            $this->fillNumber(0, 17),
            $this->fillNumber(400, 5),
            $this->fillNumber(0, 17),
            $this->fillNumber(0, 17),
            $this->fillNumber(1000, 5),
            $this->fillNumber(0, 17),
            $this->fillNumber($accruedTax->base, 17),
            $this->fillNumber($accruedTax->rate, 5),
            $this->fillNumber($accruedTax->tax, 17),
            $this->fillNumber(0, 102),
            $this->fillNumber(0, 17),
            $this->fillNumber(520, 5),
            $this->fillNumber(0, 17),
            $this->fillNumber(0, 17),
            $this->fillNumber(140, 5),
            $this->fillNumber(0, 17),
            $this->fillNumber(0, 17),
            $this->fillNumber(50, 5),
            $this->fillNumber(0, 17),
            $this->fillNumber(0, 17),
            $this->fillNumber(0, 17),
            $this->fillNumber($accruedTax->tax, 17),
        ]);
    }

    private function generateDeductibleTaxData(
        DeductibleTax $bottom_line,
        AccruedTax $top_line,
    ): string {
        $tax_result = $top_line->tax - $bottom_line->tax;

        return implode('', [
            $this->fillNumber($bottom_line->base, 17),
            $this->fillNumber($bottom_line->tax, 17),
            $this->fillNumber(0, 255),
            $this->fillNumber($bottom_line->tax, 17),
            $this->fillNumber($tax_result, 17),
        ]);
    }

    private function generatePage3(
        AccruedTax $accruedTax,
        DeductibleTax $deductibleTax,
        string $IBAN,
    ): string {
        return implode('', [
            "<T30303000>",
            $this->fillNumber(0, 170),
            $this->generateResult($accruedTax, $deductibleTax),
            $this->generateOtherData($IBAN),
            "</T30303000>",
        ]);
    }

    private function generateResult(
        AccruedTax $accruedTax,
        DeductibleTax $deductibleTax,
    ): string {
        $result = $accruedTax->tax - $deductibleTax->tax;
        return implode('', [
            $this->fillNumber(0, 17),
            $this->fillNumber($result, 17),
            $this->fillNumber(100_00, 5),
            $this->fillNumber($result, 17),
            $this->fillNumber(0, 17),
            $this->fillNumber(0, 17),
            $this->fillNumber(0, 17),
            $this->fillNumber(0, 17),
            $this->fillNumber(0, 17),
            $this->fillNumber($result, 17),
            $this->fillNumber(0, 17),
            $this->fillNumber($result, 17),
        ]);
    }

    private function fillNumber(int $number, int $size): string
    {
        $formattedNumber = str_repeat('0', $size - strlen(abs($number))) . abs($number);

        if ($number < 0) {
            $formattedNumber[0] = "N";
        }

        return $formattedNumber;
    }

    private function generateOtherData(string $IBAN): string
    {
        return implode('', [
            ' ',
            $this->padding(13),
            ' ',
            $this->padding(11),
            $IBAN,
            $this->padding(765),
        ]);
    }
}
