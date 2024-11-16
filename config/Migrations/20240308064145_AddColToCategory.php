<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class AddColToCategory extends AbstractMigration
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
        $table = $this->table('category');
        $table->addColumn('status','tinyinteger',[
            'default' => 1,
            'limit' => 1,
        ]);
        $table->update();
    }
}
