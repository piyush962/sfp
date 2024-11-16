<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Orders Model
 *
 * @property \App\Model\Table\OrdersTable&\Cake\ORM\Association\BelongsTo $Orders
 * @property \App\Model\Table\OrdersTable&\Cake\ORM\Association\HasMany $Orders
 *
 * @method \App\Model\Entity\Order newEmptyEntity()
 * @method \App\Model\Entity\Order newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Order> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Order get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Order findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Order patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Order> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Order|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Order saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Order>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Order>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Order>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Order> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Order>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Order>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Order>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Order> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class OrdersTable extends Table
{
    /**
     * Initialize method
     *
     * @param array<string, mixed> $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('orders');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
        $this->belongsTo('Users', [
            'foreignKey' => 'client_id',
            'joinType' => 'INNER',
        ]);
        
        $this->belongsTo('Category', [
            'foreignKey' => 'p_category',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Products', [
            'foreignKey' => 'p_name',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('ProductDetails', [
            'foreignKey' => 'p_name',
            'joinType' => 'INNER',
        ]);
        $this->addBehavior('Timestamp');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('client_id')
            ->allowEmptyString('client_id');

        $validator
            ->scalar('order_id')
            ->maxLength('order_id', 20)
            ->allowEmptyString('order_id');

        $validator
            ->scalar('p_name')
            ->maxLength('p_name', 100)
            ->allowEmptyString('p_name');
        $validator
            ->scalar('packaging_name')
            ->maxLength('packaging_name', 100)
            ->allowEmptyString('packaging_name');
            
        $validator
            ->scalar('p_dimensions')
            ->maxLength('p_dimensions', 100)
            ->allowEmptyString('p_dimensions');

        $validator
            ->integer('p_material')
            ->allowEmptyString('p_material');

        $validator
            ->integer('quantity')
            ->allowEmptyString('quantity');

        $validator
            ->decimal('unit_price')
            ->allowEmptyString('unit_price');
        $validator
            ->decimal('total_price')
            ->allowEmptyString('total_price');
        $validator
            ->integer('p_category')
            ->allowEmptyString('p_category');

        $validator
            ->integer('p_subcategory')
            ->allowEmptyString('p_subcategory');
        $validator
            ->scalar('status')
            ->maxLength('status', 11)
            ->allowEmptyString('status');
        $validator
            ->dateTime('order_date')
            ->allowEmptyDateTime('order_date');

        $validator
            ->dateTime('order_deadline')
            ->allowEmptyDateTime('order_deadline');

        // $validator
        //     ->scalar('image')
        //     ->maxLength('image', 50)
        //     ->allowEmptyFile('image');

        $validator
            ->scalar('send_reminder')
            ->maxLength('send_reminder', 20)
            ->allowEmptyString('send_reminder');

        $validator
            ->integer('ship_to')
            ->allowEmptyString('ship_to');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->isUnique(['order_id']), ['errorField' => 'id']);
        return $rules;
    }
}
