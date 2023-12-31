<?php

namespace Test\Unit\Domain\Entities;

use App\Domain\Entities\TaxAgency303Form;
use App\Domain\ValueObject\AccruedTax;
use App\Domain\ValueObject\DeclarationPeriod;
use App\Domain\ValueObject\DeductibleTax;
use App\Domain\ValueObject\Money;
use PHPUnit\Framework\TestCase;

class TaxAgency303FromTest extends TestCase
{
    public function test_when_result_is_negative_declaration_type_is_compensation_request(): void
    {
        $form = $this->buildFormWith(100, 200);
        
        $actual_type = $form->declarationType();
        
        self::assertEquals(TaxAgency303Form::TYPE_COMPENSATION_REQUEST, $actual_type);
    }
    
    public function test_when_no_activity_declaration_type_is_na_or_zero(): void
    {
        $form = $this->buildFormWith(0,0);
        
        $actual_type = $form->declarationType();
        
        self::assertEquals(TaxAgency303Form::TYPE_NO_ACTIVITY_OR_ZERO_RESULT, $actual_type);
    }
    
    public function test_positive_result_with_enough_pending_declaration_type_is_na_or_zero(): void
    {
        $form = $this->buildFormWith(100, 0, 200);
        
        $actual_type = $form->declarationType();
        
        self::assertEquals(TaxAgency303Form::TYPE_NO_ACTIVITY_OR_ZERO_RESULT, $actual_type);
    }
    
    public function test_positive_result_with_no_enough_pending_declaration_type_is_income(): void
    {
        $form = $this->buildFormWith(100, 0);
        
        $actual_type = $form->declarationType();
        
        self::assertEquals(TaxAgency303Form::TYPE_INCOME, $actual_type);
    }
    
    public function test_tax_due_is_difference_between_accrued_and_deductible(): void
    {
        $form = $this->buildFormWith(200, 100);
        
        $tax_due = $form->taxDue();
        
        self::assertEquals(21, $tax_due);
    }
    
    public function test_when_previous_amount_is_zero_then_max_amount_to_compensate_is_zero(): void
    {
        $form = $this->buildFormWith(200, 100);
        
        $max_amount_to_compensate = $form->maxAmountToCompensate();
        
        self::assertEquals(0, $max_amount_to_compensate);
    }
    
    public function test_when_tax_due_is_negative_then_max_amount_to_compensate_is_zero(): void
    {
        $form = $this->buildFormWith(100, 200, 300);

        $max_amount_to_compensate = $form->maxAmountToCompensate();

        self::assertEquals(0, $max_amount_to_compensate);
    }
    
    public function test_when_pending_is_greater_than_tax_due_then_max_amount_to_compensate_is_tax_due(): void
    {
        $form = $this->buildFormWith(200, 100, 100);

        $max_amount_to_compensate = $form->maxAmountToCompensate();

        self::assertEquals(21, $max_amount_to_compensate);
    }
    
    public function test_when_pending_is_less_than_tax_due_then_max_amount_to_compensate_is_pending(): void
    {
        $form = $this->buildFormWith(2000, 1000, 100);

        $max_amount_to_compensate = $form->maxAmountToCompensate();

        self::assertEquals(100, $max_amount_to_compensate);
    }

    private function buildFormWith(int $incomes, int $expenses, int $pending = 0): TaxAgency303Form
    {
        return new TaxAgency303Form(
            "whatever",
            "My Name",
            2023,
            DeclarationPeriod::QUARTER(1),
            new AccruedTax($incomes, 21_00, $incomes * 21 / 100),
            new DeductibleTax($expenses, $expenses * 21 / 100),
            'ANIBAAAAAAAAAAAAAAN',
            new Money($pending),
        );
    }
}