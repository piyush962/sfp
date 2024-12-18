<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class AddColOrdeToOrders extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */
    public function change(): void
    {
        $table = $this->table('orders');
        $table->addColumn('packaging_name', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true,
            'after'=>'p_name'
        ]);
        $table->update();
    }
}
