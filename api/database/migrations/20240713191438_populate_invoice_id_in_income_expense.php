<?php
declare(strict_types=1);

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Db\Adapter\SQLiteAdapter;
use Phinx\Migration\AbstractMigration;

final class PopulateInvoiceIdInIncomeExpense extends AbstractMigration
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
        $adapter = $this->getAdapter();
        
        $query = '';
        if ($adapter instanceof MysqlAdapter) {
            $query = '
                UPDATE income
                INNER JOIN invoice ON income.id = invoice.income_id
                SET income.invoice_id = invoice.id;
            ';
        } elseif ($adapter instanceof SQLiteAdapter) {
            $query = '
                UPDATE income
                SET invoice_id = (
                    SELECT id
                    FROM invoice
                    WHERE income.id = invoice.income_id
                )
                WHERE EXISTS (
                    SELECT 1
                    FROM invoice
                    WHERE income.id = invoice.income_id
                );
            ';        
        }
        $this->execute($query);
    }
}
