<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class AddColOtpToUsers extends AbstractMigration
{
  
    public function change(): void
    {
        $table = $this->table('users');
        $table->addColumn('otp', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => true,
            'after'=>'password'
        ]);
        $table->update();
    }
}
