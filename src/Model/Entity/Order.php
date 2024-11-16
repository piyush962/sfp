<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Order Entity
 *
 * @property int $id
 * @property int|null $client_id
 * @property string|null $order_id
 * @property string|null $p_name
 * @property string|null $p_dimensions
 * @property int|null $p_material
 * @property int|null $quantity
 * @property string|null $unit_price
 * @property int|null $p_category
 * @property int|null $p_subcategory
 * @property \Cake\I18n\DateTime|null $order_date
 * @property \Cake\I18n\DateTime|null $order_deadline
 * @property string|null $image
 * @property string|null $send_reminder
 * @property int|null $ship_to
 * @property \Cake\I18n\DateTime|null $created
 * @property \Cake\I18n\DateTime|null $modified
 *
 * @property \App\Model\Entity\Order[] $orders
 */
class Order extends Entity
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
        'client_id' => true,
        'order_id' => true,
        'p_name' => true,
        'packaging_name'=>true,
        'p_dimensions' => true,
        'p_material' => true,
        'quantity' => true,
        'unit_price' => true,
        'total_price' => true,
        'order_date' => true,
        'order_deadline' => true,
        'send_reminder' => true,
        'ship_to' => true,
        'status' => true,
        'created' => true,
        'modified' => true,
        'orders' => true,
    ];
}
