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

    /*public function autoAssignAndSchedule(int $clientId): ?array
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

        // Step 1: Filter eligible inspectors based on status and specialization coverage
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

        // Step 2: Get scheduled load per inspector per date
        $inspectorLoad = $this->find()
            ->select(['inspector_id', 'scheduled_date', 'count' => $this->find()->func()->count('*')])
            ->where(['scheduled_date >=' => FrozenDate::today()])
            ->group(['inspector_id', 'scheduled_date'])
            ->combine('inspector_id', 'count', 'scheduled_date') // [inspector_id][date] => count
            ->toArray();

        // Step 3: Sort inspectors by total future load
        usort($candidates, function ($a, $b) use ($inspectorLoad) {
            $loadA = array_sum($inspectorLoad[$a->id] ?? []);
            $loadB = array_sum($inspectorLoad[$b->id] ?? []);
            return $loadA <=> $loadB;
        });

        // Step 4: Find the earliest available date for each candidate respecting max 2 inspections/day
        foreach ($candidates as $inspector) {
            $availableSlots = $availabilities->find()
                ->where([
                    'inspector_id' => $inspector->id,
                    'is_available' => true,
                    'available_date >=' => FrozenDate::today()
                ])
                ->order(['available_date' => 'ASC'])
                ->toArray();

            foreach ($availableSlots as $slot) {
                $dateStr = $slot->available_date->format('Y-m-d');
                $currentLoad = $inspectorLoad[$inspector->id][$dateStr] ?? 0;

                if ($currentLoad < 2) { // Max 2 inspections per day
                    $slot->is_available = false;
                    $slot->reason = 'Auto-assigned for inspection';
                    $availabilities->save($slot);

                    \Cake\Log\Log::info("Assigned inspector #{$inspector->id} to client #{$clientId} on {$dateStr}");

                    return [
                        'inspector_id' => $inspector->id,
                        'scheduled_date' => $slot->available_date
                    ];
                }
            }
        }

        \Cake\Log\Log::warning("No available date found for matched inspectors for client #{$clientId}");
        return null;
    }
*/

    /*public function autoCreateForAllClients(): array
    {
        $clientsTable = TableRegistry::getTableLocator()->get('Clients');
        $allClients = $clientsTable->find()->select(['id'])->toArray();

        $results = [
            'created' => 0,
            'failed' => 0,
            'existing' => 0
        ];

        foreach ($allClients as $client) {
            $existing = $this->find()
                ->where(['client_id' => $client->id])
                ->first();

            if ($existing) {
                $results['existing']++;
                continue;
            }

            if ($this->autoCreateForClient($client->id)) {
                $results['created']++;
            } else {
                $results['failed']++;
            }
        }

        return $results;
    }*/

    /*
    public function autoCreateForClient(int $clientId): bool
    {
        try {
            \Cake\Log\Log::info("Starting autoCreateForClient for client #{$clientId}");

            // Check if inspection already exists for this client
            $existing = $this->find()
                ->where(['client_id' => $clientId])
                ->first();

            if ($existing) {
                \Cake\Log\Log::info("Inspection already exists for client #{$clientId}");
                return true;
            }

            $clients = TableRegistry::getTableLocator()->get('Clients');
            $client = $clients->get($clientId);
            \Cake\Log\Log::info("Client found: #{$clientId}, type: {$client->establishment_type}");

            $type = $client->establishment_type;

            $coverage = [
                'General' => ['Residential', 'Commercial'],
                'Mechanical' => ['Industrial', 'Storage'],
                'Electrical' => ['Residential', 'Commercial', 'Industrial', 'Storage', 'Assembly', 'Miscellaneous'],
                'Structural' => ['Commercial', 'Assembly'],
                'Hazardous' => ['Industrial', 'Storage', 'Miscellaneous']
            ];

            $inspectors = TableRegistry::getTableLocator()->get('Inspectors');
            $eligibleInspectors = $inspectors->find()
                ->where(['status' => 'available'])
                ->toArray();

            \Cake\Log\Log::info("Found " . count($eligibleInspectors) . " available inspectors");

            $candidates = [];
            foreach ($eligibleInspectors as $inspector) {
                if (
                    isset($coverage[$inspector->specialization]) &&
                    in_array($type, $coverage[$inspector->specialization])
                ) {
                    $candidates[] = $inspector;
                }
            }

            \Cake\Log\Log::info("Found " . count($candidates) . " eligible inspectors for type '{$type}'");

            if (empty($candidates)) {
                \Cake\Log\Log::warning("No inspector matches for client #{$clientId} with type '{$type}'");

                // Create inspection without inspector
                $inspection = $this->newEntity([
                    'client_id' => $clientId,
                    'status' => 'pending',
                ]);

                $result = $this->save($inspection);
                \Cake\Log\Log::info("Created inspection without inspector: " . ($result ? 'success' : 'failed'));
                return (bool)$result;
            }

            // ... rest of your assignment logic ...

            foreach ($candidates as $inspector) {
                $availabilities = TableRegistry::getTableLocator()->get('Availabilities');
                $available = $availabilities->find()
                    ->where([
                        'inspector_id' => $inspector->id,
                        'is_available' => true,
                        'available_date >=' => FrozenDate::today(),
                    ])
                    ->order(['available_date' => 'ASC'])
                    ->first();

                if ($available) {
                    \Cake\Log\Log::info("Found available slot for inspector #{$inspector->id} on {$available->available_date}");

                    $available->is_available = false;
                    $available->reason = 'Auto-assigned for inspection';

                    if ($availabilities->save($available)) {
                        \Cake\Log\Log::info("Saved availability update for inspector #{$inspector->id}");

                        // Create the inspection
                        $inspection = $this->newEntity([
                            'client_id' => $clientId,
                            'inspector_id' => $inspector->id,
                            'scheduled_date' => $available->available_date,
                            'status' => 'scheduled',
                        ]);

                        $result = $this->save($inspection);
                        \Cake\Log\Log::info("Created inspection with inspector: " . ($result ? 'success' : 'failed'));

                        if (!$result) {
                            \Cake\Log\Log::error("Inspection save errors: " . json_encode($inspection->getErrors()));
                        }

                        return (bool)$result;
                    } else {
                        \Cake\Log\Log::error("Failed to save availability for inspector #{$inspector->id}");
                    }
                }
            }

            \Cake\Log\Log::warning("No available dates found for matched inspectors for client #{$clientId}");

            // Create inspection without scheduled date
            $inspection = $this->newEntity([
                'client_id' => $clientId,
                'status' => 'pending',
            ]);

            $result = $this->save($inspection);
            \Cake\Log\Log::info("Created pending inspection: " . ($result ? 'success' : 'failed'));
            return (bool)$result;
        } catch (\Exception $e) {
            \Cake\Log\Log::error("Auto-creation failed for client #{$clientId}: " . $e->getMessage());
            return false;
        }
    }
    */

    public function beforeSave(EventInterface $event, EntityInterface $entity, \ArrayObject $options)
    {
        /*
        if (
            $entity->isNew() &&
            empty($entity->inspector_id) &&
            !empty($entity->client_id)
        ) {
            // Use the ClientsTable method instead
            $clientsTable = TableRegistry::getTableLocator()->get('Clients');
            $inspectionsTable = TableRegistry::getTableLocator()->get('Inspections');
            $clientsTable->fullAutoCreateForClient($inspectionsTable, $entity->client_id);

            // Since fullAutoCreateForClient creates the inspection directly,
            // we should probably return false to prevent duplicate saves
            return false;
        }
        */
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
