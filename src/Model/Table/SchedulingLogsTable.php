<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SchedulingLogs Model
 *
 * @property \App\Model\Table\InspectionsTable&\Cake\ORM\Association\BelongsTo $Inspections
 *
 * @method \App\Model\Entity\SchedulingLog newEmptyEntity()
 * @method \App\Model\Entity\SchedulingLog newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\SchedulingLog[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\SchedulingLog get($primaryKey, $options = [])
 * @method \App\Model\Entity\SchedulingLog findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\SchedulingLog patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\SchedulingLog[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\SchedulingLog|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SchedulingLog saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SchedulingLog[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\SchedulingLog[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\SchedulingLog[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\SchedulingLog[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class SchedulingLogsTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('scheduling_logs');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Inspections', [
            'foreignKey' => 'inspection_id',
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
            ->integer('inspection_id')
            ->notEmptyString('inspection_id');

        $validator
            ->date('old_date')
            ->requirePresence('old_date', 'create')
            ->notEmptyDate('old_date');

        $validator
            ->date('new_date')
            ->requirePresence('new_date', 'create')
            ->notEmptyDate('new_date');

        $validator
            ->scalar('reason')
            ->allowEmptyString('reason');

        $validator
            ->integer('updated_by')
            ->requirePresence('updated_by', 'create')
            ->notEmptyString('updated_by');

        $validator
            ->dateTime('created_at')
            ->allowEmptyDateTime('created_at');

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
        $rules->add($rules->existsIn('inspection_id', 'Inspections'), ['errorField' => 'inspection_id']);

        return $rules;
    }
}
