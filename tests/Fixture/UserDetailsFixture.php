<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * UserDetailsFixture
 */
class UserDetailsFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'user_id' => 1,
                'shipping_address' => 'Lorem ipsum dolor sit amet',
                'billing_address' => 'Lorem ipsum dolor sit amet',
                'modified' => '2024-03-01 11:26:08',
                'created' => '2024-03-01 11:26:08',
            ],
        ];
        parent::init();
    }
}
