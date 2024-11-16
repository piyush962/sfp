<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateOrders extends AbstractMigration
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
        $table = $this->table('orders',[
            'collation' => 'utf8mb4_unicode_ci'
        ]);
        $table->addColumn('client_id', 'integer', [
            'default' => 0,
            'limit' => 11,
            'null' => true,
        ]);
        $table->addColumn('order_id', 'string', [
            'default' => null,
            'limit' => 20,
            'null' => true,
        ]);
        $table->addColumn('p_name', 'string', [
            'default' => null,
            'limit' => 100,
            'null' => true,
        ]);
        $table->addColumn('p_dimensions', 'string', [
            'default' => null,
            'limit' => 100,
            'null' => true,
        ]);
        $table->addColumn('p_material', 'integer', [
            'default' => 0,
            'limit' => 11,
            'null' => true,
        ]);
        $table->addColumn('quantity', 'integer', [
            'default' => 0,
            'limit' => 11,
            'null' => true, 
        ]);
        $table->addColumn('unit_price', 'decimal', [
            'precision' => 10,
            'scale' => 2,
            'default' => null,
            'null' => true,
        ]);

       
        $table->addColumn('p_category', 'integer', [
            'default' => 0,
            'limit' => 11,
            'null' => true,
        ]);
        $table->addColumn('p_subcategory', 'integer', [
            'default' => 0,
            'limit' => 11,
            'null' => true,
        ]);
        $table->addColumn('order_date', 'datetime', [
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('order_deadline', 'datetime', [
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('image', 'string', [
            'default' => null,
            'limit' => 50,
            'null' => true,
        ]);
        $table->addColumn('send_reminder', 'string', [
            'default' => null,
            'limit' => 20,
            'null' => true,
        ]);
        $table->addColumn('ship_to', 'integer', [
            'default' => 0,
            'limit' => 11, 
            'null' => true,
        ]);
        $table->addColumn('created', 'datetime', [
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('modified', 'datetime', [
            'default' => null,
            'null' => true,
        ]);
        $table->create();
    }
}
