<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class InvoiceIdInIncomeExpense extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change(): void
    {
        $income_table = $this->table('income');
        $income_table->addColumn('invoice_id', 'integer');
        $income_table->save();

        $expense_table = $this->table('expense');
        $expense_table->addColumn('invoice_id', 'integer');
        $expense_table->save();
    }
}
