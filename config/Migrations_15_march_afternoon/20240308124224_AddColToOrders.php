<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class AddColToOrders extends AbstractMigration
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
        $table->addColumn('status', 'integer', [
            'default' => 1,
            'limit' => 11,
            'comment'=>"0 for cancel & 1 for in-process & 2 for dispatch & 3 for deliver" ,
            'after'=>'ship_to'
        ]);
        $table->update();
    }
}
