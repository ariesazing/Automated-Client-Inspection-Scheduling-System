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

    public function autoAssignAndSchedule(int $clientId): ?array
    {
        $clients = TableRegistry::getTableLocator()->get('Clients');
        $inspectors = TableRegistry::getTableLocator()->get('Inspectors');
        $availabilities = TableRegistry::getTableLocator()->get('Availabilities');

        $client = $clients->get($clientId);
        $type = $client->establishment_type;

        $coverage = [
            'General' => ['Residential', 'Commercial'],
            'Mechanical' => ['Industrial', 'Storage'],
            'Electrical' => ['Residential', 'Commercial', 'Industrial', 'Storage', 'Assembly', 'Miscellaneous'],
            'Structural' => ['Commercial', 'Assembly'],
            'Hazardous' => ['Industrial', 'Storage', 'Miscellaneous']
        ];

        $eligibleInspectors = $inspectors->find()
            ->where(['status' => 'available'])
            ->toArray();

        $candidates = [];
        foreach ($eligibleInspectors as $inspector) {
            if (isset($coverage[$inspector->specialization]) && in_array($type, $coverage[$inspector->specialization])) {
                $candidates[] = $inspector;
            }
        }

        if (empty($candidates)) {
            \Cake\Log\Log::warning("No inspector matches for client #{$clientId} with type '{$type}'.");
            return null;
        }

        $inspectorLoad = $this->find()
            ->select(['inspector_id', 'count' => $this->find()->func()->count('*')])
            ->where(['scheduled_date >=' => FrozenDate::today()])
            ->group('inspector_id')
            ->combine('inspector_id', 'count')
            ->toArray();

        usort($candidates, function ($a, $b) use ($inspectorLoad) {
            $loadA = $inspectorLoad[$a->id] ?? 0;
            $loadB = $inspectorLoad[$b->id] ?? 0;
            return $loadA <=> $loadB;
        });

        foreach ($candidates as $inspector) {
            $available = $availabilities->find()
                ->where([
                    'inspector_id' => $inspector->id,
                    'is_available' => true,
                    'available_date >=' => FrozenDate::today(),
                ])
                ->order(['available_date' => 'ASC'])
                ->first();

            if ($available) {
                $available->is_available = false;
                $available->reason = 'Auto-assigned for inspection';
                $availabilities->save($available);

                \Cake\Log\Log::info("Assigned inspector #{$inspector->id} to client #{$clientId} for {$available->available_date}");

                return [
                    'inspector_id' => $inspector->id,
                    'scheduled_date' => $available->available_date
                ];
            }
        }

        \Cake\Log\Log::warning("No available date found for matched inspectors for client #{$clientId}");
        return null;
    }

    public function beforeSave(EventInterface $event, EntityInterface $entity, \ArrayObject $options)
    {
        if (
            $entity->isNew() &&
            empty($entity->inspector_id) &&
            !empty($entity->client_id)
        ) {
            $assignment = $this->autoAssignAndSchedule($entity->client_id);
            if (!empty($assignment)) {
                $entity->inspector_id = $assignment['inspector_id'];
                $entity->scheduled_date = $assignment['scheduled_date'];
                $entity->status = 'scheduled';
            } else {
                $entity->status = 'pending';
            }
        }

        if (!$entity->isNew() && $entity->isDirty('scheduled_date')) {
            $logsTable = TableRegistry::getTableLocator()->get('SchedulingLogs');
            $logsTable->save($logsTable->newEntity([
                'inspection_id' => $entity->id,
                'old_date' => $entity->getOriginal('scheduled_date'),
                'new_date' => $entity->scheduled_date,
                'reason' => 'Rescheduled by system or user',
                'updated_by' => $options['userId'] ?? null,
            ]));
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

        $validator
            ->boolean('manual_override')
            ->allowEmptyString('manual_override');

        return $validator;
    }

    public function buildRules(\Cake\ORM\RulesChecker $rules): \Cake\ORM\RulesChecker
    {
        $rules->add($rules->existsIn('client_id', 'Clients'), ['errorField' => 'client_id']);
        $rules->add($rules->existsIn('inspector_id', 'Inspectors'), ['errorField' => 'inspector_id']);
        return $rules;
    }
}