<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class AddColPriceToOrders extends AbstractMigration
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
        $table->addColumn('total_price', 'decimal', [
            'precision' => 10,
            'scale' => 2,
            'default' => null,
            'null' => true,
            'after' => 'unit_price'
        ]);
        $table->update();
    }
}
