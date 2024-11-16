<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class AddColOrdToOrders extends AbstractMigration
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
        $table->changeColumn('p_name', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => true,
        ]);
        $table->changeColumn('p_dimensions', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => true,
        ]);
        $table->changeColumn('p_material', 'integer', [
            'default' => 0,
            'limit' => 11,
            'null' => true,
        ]);
        $table->removeColumn('p_category');       
        $table->removeColumn('p_subcategory');
        $table->update();
    }
}
