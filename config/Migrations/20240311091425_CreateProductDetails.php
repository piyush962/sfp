<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateProductDetails extends AbstractMigration
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
        $table = $this->table('product_details',[
            'collation' => 'utf8mb4_unicode_ci'
        ]);
        $table->addColumn('product_id', 'integer', [
            'default' => 0,
            'limit' => 11,
            'null' => true,
        ]);       
        $table->addColumn('p_dimension', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
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