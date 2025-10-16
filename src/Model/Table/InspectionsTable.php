<?php

declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Event\EventInterface;
use Cake\Datasource\EntityInterface;

/**
 * Inspections Model
 *
 * @property \App\Model\Table\ClientsTable&\Cake\ORM\Association\BelongsTo $Clients
 * @property \App\Model\Table\InspectorsTable&\Cake\ORM\Association\BelongsTo $Inspectors
 * @property \App\Model\Table\InspectionResultsTable&\Cake\ORM\Association\HasMany $InspectionResults
 * @property \App\Model\Table\SchedulingLogsTable&\Cake\ORM\Association\HasMany $SchedulingLogs
 *
 * @method \App\Model\Entity\Inspection newEmptyEntity()
 * @method \App\Model\Entity\Inspection newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Inspection[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Inspection get($primaryKey, $options = [])
 * @method \App\Model\Entity\Inspection findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Inspection patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Inspection[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Inspection|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Inspection saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Inspection[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Inspection[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Inspection[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Inspection[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class InspectionsTable extends Table
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

        $this->setTable('inspections');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Clients', [
            'foreignKey' => 'client_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Inspectors', [
            'foreignKey' => 'inspector_id',
            'joinType' => 'INNER',
        ]);
        $this->hasMany('InspectionResults', [
            'foreignKey' => 'inspection_id',
        ]);
        $this->hasMany('SchedulingLogs', [
            'foreignKey' => 'inspection_id',
        ]);
    }

    public function beforeSave(EventInterface $event, EntityInterface $entity, $options)
    {
        // Only if this is an existing record being updated
        if (!$entity->isNew() && $entity->isDirty('scheduled_date')) {
            $original = $entity->getOriginal('scheduled_date');
            $new = $entity->scheduled_date;

            // Get user info (optional if you're using Authentication)
            $userId = $options['userId'] ?? null;

            // Create the log entry
            $log = $this->SchedulingLogs->newEntity([
                'inspection_id' => $entity->id,
                'old_date' => $original,
                'new_date' => $new,
                'reason' => 'Auto-log: schedule date changed',
                'updated_by' => $userId,
            ]);
            $this->SchedulingLogs->save($log);
        }
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
            ->notEmptyString('client_id');

        $validator
            ->integer('inspector_id')
            ->notEmptyString('inspector_id');

        $validator
            ->date('scheduled_date')
            ->requirePresence('scheduled_date', 'create')
            ->notEmptyDate('scheduled_date');

        $validator
            ->date('actual_date')
            ->allowEmptyDate('actual_date');

        $validator
            ->scalar('status')
            ->allowEmptyString('status');

        $validator
            ->scalar('remarks')
            ->allowEmptyString('remarks');

        $validator
            ->dateTime('created_at')
            ->allowEmptyDateTime('created_at');

        $validator
            ->dateTime('updated_at')
            ->allowEmptyDateTime('updated_at');

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
        $rules->add($rules->existsIn('client_id', 'Clients'), ['errorField' => 'client_id']);
        $rules->add($rules->existsIn('inspector_id', 'Inspectors'), ['errorField' => 'inspector_id']);

        return $rules;
    }
}
