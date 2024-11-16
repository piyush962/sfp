<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateNotifications extends AbstractMigration
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
        $table = $this->table('notifications',[
            'collation' => 'utf8mb4_unicode_ci'
        ]);
        $table->addColumn('customer_id', 'integer', [
            'default' => 0,
            'limit' => 11,
            'null' => true,
        ]);
        $table->addColumn('order_id', 'integer', [
            'default' => 0,
            'limit' => 11,
            'null' => true,
        ]);
        $table->addColumn('message', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true,
        ]);
        $table->addColumn('type', 'tinyinteger', [
            'default' => 0,
            'null' => true,
            
        ]);
        $table->addColumn('notification_date', 'date', [
            'default' => null,
            'null' => true,
            
        ]);
        
        $table->addColumn('created', 'date', [
            'default' => null,
            'null' => true,
            
        ]);
        $table->create();
    }
}
