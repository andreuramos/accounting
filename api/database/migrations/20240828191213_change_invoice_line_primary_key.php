<?php
declare(strict_types=1);

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Db\Adapter\SQLiteAdapter;
use Phinx\Migration\AbstractMigration;

final class ChangeInvoiceLinePrimaryKey extends AbstractMigration
{
    public function change(): void
    {
        $this->table('invoice_line')
            ->addColumn('position', 'integer')
            ->save();
        
        $this->execute('UPDATE invoice_line SET position = id');
        
        $adapter = $this->getAdapter();
        if ($adapter instanceof MysqlAdapter) {
            $this->table('invoice_line')
                ->changePrimaryKey(['invoice_id', 'position'])
                ->removeColumn('id')
                ->save();
        } else {
            if ($adapter instanceof SQLiteAdapter) {
                $this->table('invoice_line')
                    ->changePrimaryKey('invoice_id, position')
                    ->removeColumn('id')
                    ->save();
            }
            }
        }
    }
