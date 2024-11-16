<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class AddPriceToUsers extends AbstractMigration
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
        $table->addColumn('owner_name', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
            'after' => 'password',
        ])->addColumn('phone_number', 'string', [
            'default' => null,
            'limit' => 11,
            'null' => false,
            'after' => 'owner_name',
        ])->addColumn('shipping_address', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
            'after' => 'phone_number',
        ])->addColumn('billing_address', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
            'after' => 'shipping_address',
        ]);

        $table->update();
    }
}
