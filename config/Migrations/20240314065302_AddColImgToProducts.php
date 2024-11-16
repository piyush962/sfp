<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class AddColImgToProducts extends AbstractMigration
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
        $table = $this->table('products',[
            'collation' => 'utf8mb4_unicode_ci'
        ]);
        $table->addColumn('image', 'string', [
            'default' => null,
            'limit' => 50,
            'null' => true,
            'after'=>'p_category'
        ]);
        $table->update();
    }
}
