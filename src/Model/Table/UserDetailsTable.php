<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * UserDetails Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\UserDetail newEmptyEntity()
 * @method \App\Model\Entity\UserDetail newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\UserDetail> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\UserDetail get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\UserDetail findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\UserDetail patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\UserDetail> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\UserDetail|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\UserDetail saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\UserDetail>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\UserDetail>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\UserDetail>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\UserDetail> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\UserDetail>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\UserDetail>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\UserDetail>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\UserDetail> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class UserDetailsTable extends Table
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

        $this->setTable('user_details');
        $this->setDisplayField('shipping_address');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER',
        ]);
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
            ->integer('user_id')
            ->notEmptyString('user_id');

        $validator
            ->scalar('shipping_address')
            ->maxLength('shipping_address', 255)
            ->requirePresence('shipping_address', 'create')
            ->notEmptyString('shipping_address');

        $validator
            ->scalar('billing_address')
            ->maxLength('billing_address', 255)
            ->requirePresence('billing_address', 'create')
            ->notEmptyString('billing_address');

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
        $rules->add($rules->existsIn(['user_id'], 'Users'), ['errorField' => 'user_id']);

        return $rules;
    }
}
