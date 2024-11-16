<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class AddUpdateToUsers extends AbstractMigration
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
        $table = $this->table('users');
        $table->addColumn('company_name', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
            'after' => 'owner_name',
        ]);
        $table->removeColumn('shipping_address');
        $table->removeColumn('billing_address');
        $table->update();
    }
}
