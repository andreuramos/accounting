<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class V20230522194620 extends AbstractMigration
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
        $table = $this->table('invoice');
        $table->addColumn('number', 'string');
        $table->addIndex(['number']);
        $table->addColumn('emitter_id', 'integer');
        $table->addColumn('receiver_id', 'integer');
        $table->addColumn('date', 'date');

        $table->create();
    }
}
