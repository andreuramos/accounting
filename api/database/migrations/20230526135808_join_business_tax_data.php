<?php
declare(strict_types=1);

use Phinx\Db\Table\Column;
use Phinx\Migration\AbstractMigration;

final class JoinBusinessTaxData extends AbstractMigration
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
        $businessTable = $this->table('business');
        $businessTable->addColumn('tax_name', Column::STRING);
        $businessTable->addColumn('tax_id', Column::STRING);
        $businessTable->addColumn('tax_address', Column::STRING);
        $businessTable->addColumn('tax_zip_code', Column::STRING);
        $businessTable->save();

        if ($this->isEngineMysql()) {
            $this->execute('UPDATE `business` JOIN `tax_data` ON `business`.`taxDataId` = `tax_data`.`id` ' .
                'SET `business`.`tax_name` = `tax_data`.`tax_name`, ' .
                '`business`.`tax_id` = `tax_data`.`tax_number`, ' .
                '`business`.`tax_address` = `tax_data`.`address`, ' .
                '`business`.`tax_zip_code` = `tax_data`.`zip_code` '
            );
        }

        $taxDataTable = $this->table('tax_data');
        $taxDataTable->drop();
        $taxDataTable->save();

        $businessTable->removeColumn('taxDataId');
        $businessTable->save();
    }

    private function isEngineMysql(): bool
    {
        $connection = $this->getAdapter()->getConnection();
        $engine = $connection->getAttribute(PDO::ATTR_DRIVER_NAME);
        return $engine === 'mysql';
    }
}
