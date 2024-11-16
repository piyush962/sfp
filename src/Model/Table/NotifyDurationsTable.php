<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * NotifyDurations Model
 *
 * @method \App\Model\Entity\NotifyDuration newEmptyEntity()
 * @method \App\Model\Entity\NotifyDuration newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\NotifyDuration> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\NotifyDuration get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\NotifyDuration findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\NotifyDuration patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\NotifyDuration> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\NotifyDuration|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\NotifyDuration saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\NotifyDuration>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\NotifyDuration>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\NotifyDuration>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\NotifyDuration> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\NotifyDuration>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\NotifyDuration>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\NotifyDuration>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\NotifyDuration> deleteManyOrFail(iterable $entities, array $options = [])
 */
class NotifyDurationsTable extends Table
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

        $this->setTable('notify_durations');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
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
            ->scalar('durations')
            ->maxLength('durations', 255)
            ->allowEmptyString('durations');

        return $validator;
    }
}
