<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ProductDetails Model
 *
 * @property \App\Model\Table\ProductsTable&\Cake\ORM\Association\BelongsTo $Products
 *
 * @method \App\Model\Entity\ProductDetail newEmptyEntity()
 * @method \App\Model\Entity\ProductDetail newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\ProductDetail> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ProductDetail get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\ProductDetail findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\ProductDetail patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\ProductDetail> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\ProductDetail|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\ProductDetail saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\ProductDetail>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\ProductDetail>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\ProductDetail>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\ProductDetail> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\ProductDetail>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\ProductDetail>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\ProductDetail>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\ProductDetail> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ProductDetailsTable extends Table
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

        $this->setTable('product_details');
        $this->setDisplayField('p_dimension');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Products', [
            'foreignKey' => 'product_id',
            'joinType' => 'INNER',
        ]);
        // $this->hasMany('Orders')->setForeignKey('product_id');
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
            ->integer('product_id')
            ->allowEmptyString('product_id');

        $validator
            ->scalar('p_dimension')
            ->maxLength('p_dimension', 255)
            ->requirePresence('p_dimension', 'create')
            ->notEmptyString('p_dimension');

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
        $rules->add($rules->existsIn(['product_id'], 'Products'), ['errorField' => 'product_id']);

        return $rules;
    }
}
