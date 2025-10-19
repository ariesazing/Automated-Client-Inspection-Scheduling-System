<?php

declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Event\EventInterface;
use Cake\Datasource\EntityInterface;
use Cake\I18n\FrozenDate;
use ArrayObject;
use Cake\ORM\TableRegistry;

class InspectionsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('inspections');
        $this->addBehavior('Timestamp');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Clients', [
            'foreignKey' => 'client_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Inspectors', [
            'foreignKey' => 'inspector_id',
            'joinType' => 'LEFT',
        ]);
        $this->hasMany('InspectionResults', [
            'foreignKey' => 'inspection_id',
        ]);
        $this->hasMany('SchedulingLogs', [
            'foreignKey' => 'inspection_id',
        ]);
    }

    public function beforeSave(EventInterface $event, EntityInterface $entity, \ArrayObject $options)
    {

        if (!$entity->isNew() && $entity->isDirty('scheduled_date')) {
            $logsTable = TableRegistry::getTableLocator()->get('SchedulingLogs');
            $logsTable->save($logsTable->newEntity([
                'inspection_id' => $entity->id,
                'old_date' => $entity->getOriginal('scheduled_date'),
                'new_date' => $entity->scheduled_date,
                'updated_by' => $options['userId'] ?? null,
            ]));
        }
        if (
            ($entity->scheduled_date === null || $entity->scheduled_date->format('Y-m-d') === '0000-00-00')
        ) {
            $entity->scheduled_date = $entity->getOriginal('scheduled_date');
        }

        if ($entity->status === 'completed' && empty($entity->actual_date)) {
            $entity->actual_date = FrozenDate::today();
        }
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('client_id')
            ->notEmptyString('client_id');

        $validator
            ->integer('inspector_id')
            ->allowEmptyString('inspector_id');

        $validator
            ->date('scheduled_date')
            ->allowEmptyDate('scheduled_date');

        $validator
            ->date('actual_date')
            ->allowEmptyDate('actual_date');

        $validator
            ->scalar('status')
            ->allowEmptyString('status');

        $validator
            ->scalar('remarks')
            ->allowEmptyString('remarks');


        return $validator;
    }

    public function buildRules(\Cake\ORM\RulesChecker $rules): \Cake\ORM\RulesChecker
    {
        $rules->add($rules->existsIn('client_id', 'Clients'), ['errorField' => 'client_id']);
        $rules->add($rules->existsIn('inspector_id', 'Inspectors'), ['errorField' => 'inspector_id']);
        return $rules;
    }
}
