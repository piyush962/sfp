<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Notification Entity
 *
 * @property int $id
 * @property int|null $customer_id
 * @property int|null $order_id
 * @property string|null $client_message
 * @property int|null $type
 * @property \Cake\I18n\Date|null $notification_date
 * @property \Cake\I18n\Date|null $created
 *
 * @property \App\Model\Entity\Order $order
 */
class Notification extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected array $_accessible = [
        'customer_id' => true,
        'order_id' => true,
        'client_message' => true,
        'admin_message' => true,
        'type' => true,
        'notification_date' => true,        
        'created' => true,
    ];
}
