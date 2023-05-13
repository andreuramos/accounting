<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class InvertUserDataRelation extends AbstractMigration
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
        $userTable = $this->table('user');
        $taxDataTable = $this->table('tax_data');

        $userTable->addColumn('tax_data_id', 'integer', ['null' => true])
            ->save();
        if ($this->isEngineMysql()) {
            $this->execute('UPDATE `user` JOIN `tax_data` ON `user`.`id` = `tax_data`.`user_id` SET `user`.`tax_data_id` = `tax_data`.`id`');
        }

        $taxDataTable->removeColumn('user_id')
            ->save();
    }

    private function isEngineMysql(): bool
    {
        $connection = $this->getAdapter()->getConnection();
        $engine = $connection->getAttribute(PDO::ATTR_DRIVER_NAME);
        return $engine === 'mysql';
    }
}
