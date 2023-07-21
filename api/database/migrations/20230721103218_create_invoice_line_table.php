<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateInvoiceLineTable extends AbstractMigration
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
        $table = $this->table('invoice_line');

        $table->addColumn('invoice_id', 'integer');
        $table->addColumn('product', 'string');
        $table->addColumn('amount_cents', 'integer');
        $table->addColumn('quantity', 'integer');
        $table->addColumn('vat_percent', 'float', ['default' => '21.00']);

        $table->create();
    }
}
