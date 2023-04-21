<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddRefreshTokenColumnToUser extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('user');
        $table->addColumn('refresh_token', 'text');
        $table->save();
    }
}
