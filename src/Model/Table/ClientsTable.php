<?php

declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Event\EventInterface;
use Cake\Datasource\EntityInterface;
use ArrayObject;
use Cake\I18n\FrozenDate;
use Cake\ORM\TableRegistry;

/**
 * Clients Model
 *
 * @property \App\Model\Table\InspectionsTable&\Cake\ORM\Association\HasMany $Inspections
 *
 * @method \App\Model\Entity\Client newEmptyEntity()
 * @method \App\Model\Entity\Client newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Client[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Client get($primaryKey, $options = [])
 * @method \App\Model\Entity\Client findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Client patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Client[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Client|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Client saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Client[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Client[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Client[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Client[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class ClientsTable extends Table
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

        $this->setTable('clients');
        $this->setDisplayField('owner_name');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');

        $this->hasMany('Inspections', [
            'foreignKey' => 'client_id',
        ]);
    }


    public function afterSave(EventInterface $event, EntityInterface $entity, ArrayObject $options)
    {
        if ($entity->isNew()) {
            // Store the client ID for delayed processing
            $this->queueInspectionCreation($entity->id);
        }
    }

    private function queueInspectionCreation(int $clientId)
    {
        // Use a simple file-based queue to avoid transaction issues
        $queueFile = LOGS . 'inspection_queue.txt';
        file_put_contents($queueFile, $clientId . PHP_EOL, FILE_APPEND | LOCK_EX);

        // Process the queue immediately after request
        register_shutdown_function([$this, 'processInspectionQueue']);
    }

    public function processInspectionQueue()
    {
        $queueFile = LOGS . 'inspection_queue.txt';

        if (!file_exists($queueFile)) {
            return;
        }

        // Lock and read the queue file
        $content = file_get_contents($queueFile);
        $clientIds = array_filter(explode(PHP_EOL, $content));

        if (empty($clientIds)) {
            return;
        }

        // Clear the queue file
        file_put_contents($queueFile, '');

        // Process each client ID
        $inspectionsTable = TableRegistry::getTableLocator()->get('Inspections');

        foreach ($clientIds as $clientId) {
            if (!empty($clientId)) {
                \Cake\Log\Log::info("Processing queued inspection for client #{$clientId}");

                // Now do the full auto-assignment (outside of transaction)
                $this->fullAutoCreateForClient($inspectionsTable, (int)$clientId);
            }
        }
    }

    private function fullAutoCreateForClient($inspectionsTable, int $clientId)
    {
        \Cake\Log\Log::info("🚨 STARTING AUTO-CREATION FOR CLIENT #{$clientId}");

        try {
            // 1. Check if inspection already exists
            $existing = $inspectionsTable->find()
                ->where(['client_id' => $clientId])
                ->first();

            if ($existing) {
                \Cake\Log\Log::info("✅ Inspection already exists: #{$existing->id}");
                return true;
            }

            // 2. Get client data
            $clientsTable = TableRegistry::getTableLocator()->get('Clients');
            $client = $clientsTable->get($clientId);
            \Cake\Log\Log::info("✅ Client found: #{$client->id} - {$client->establishment_name}, Type: {$client->type}");

            // 3. Find eligible inspectors based on specialization
            $type = $client->type;
            $coverage = [
                'general' => ['residential', 'commercial'],
                'mechanical' => ['industrial', 'storage'],
                'electrical' => ['residential', 'commercial', 'industrial', 'storage', 'assembly', 'miscellaneous'],
                'structural' => ['commercial', 'assembly'],
                'hazardous' => ['industrial', 'storage', 'miscellaneous']
            ];

            $inspectors = TableRegistry::getTableLocator()->get('Inspectors');

            // Find available inspectors with matching specialization
            $eligibleInspectors = $inspectors->find()
                ->where(['status' => 'available'])
                ->toArray();

            $candidates = [];
            foreach ($eligibleInspectors as $inspector) {
                if (
                    isset($coverage[$inspector->specialization]) &&
                    in_array($type, $coverage[$inspector->specialization])
                ) {
                    $candidates[] = $inspector;
                    \Cake\Log\Log::info("🎯 Eligible inspector: #{$inspector->id} - {$inspector->specialization}");
                }
            }

            if (empty($candidates)) {
                \Cake\Log\Log::warning("❌ No eligible inspectors found for client type: {$type}");
                return false; // Don't create inspection if no matching inspectors
            }

            // 4. Find the best candidate (least busy)
            $inspectorLoad = $inspectionsTable->find()
                ->select(['inspector_id', 'count' => $inspectionsTable->find()->func()->count('*')])
                ->where(['scheduled_date >=' => FrozenDate::today()])
                ->group('inspector_id')
                ->combine('inspector_id', 'count')
                ->toArray();

            usort($candidates, function ($a, $b) use ($inspectorLoad) {
                $loadA = $inspectorLoad[$a->id] ?? 0;
                $loadB = $inspectorLoad[$b->id] ?? 0;
                return $loadA <=> $loadB;
            });

            // 5. Find available date for the best candidate
            $availabilities = TableRegistry::getTableLocator()->get('Availabilities');
            $selectedInspector = null;
            $availableDate = null;

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
                    $selectedInspector = $inspector;
                    $availableDate = $available->available_date;
                    \Cake\Log\Log::info("✅ Found availability for inspector #{$inspector->id} on {$availableDate}");
                    break;
                }
            }

            if (!$selectedInspector || !$availableDate) {
                \Cake\Log\Log::warning("❌ No available dates found for eligible inspectors");
                return false; // Don't create inspection if no available dates
            }

            // 6. Reserve the availability
            $available->is_available = false;
            $available->reason = 'Auto-assigned for inspection';
            $availabilities->save($available);

            // 7. Create the inspection with BOTH required fields
            $inspectionData = [
                'client_id' => $clientId,
                'inspector_id' => $selectedInspector->id,
                'scheduled_date' => $availableDate,
                'status' => 'scheduled'
            ];

            \Cake\Log\Log::info("📝 Creating inspection with data: " . json_encode($inspectionData));

            $inspection = $inspectionsTable->newEntity($inspectionData);

            if ($inspection->hasErrors()) {
                \Cake\Log\Log::error("❌ VALIDATION ERRORS: " . json_encode($inspection->getErrors()));
                return false;
            }

            $result = $inspectionsTable->save($inspection);

            if ($result) {
                \Cake\Log\Log::info("🎉 SUCCESS! Inspection created with ID: #{$inspection->id}");
                \Cake\Log\Log::info("📊 Inspector: #{$selectedInspector->id}, Date: {$availableDate}");
                return true;
            } else {
                \Cake\Log\Log::error("💥 FAILED! Could not save inspection");
                return false;
            }
        } catch (\Exception $e) {
            \Cake\Log\Log::error("💀 EXCEPTION: " . $e->getMessage());
            return false;
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
            ->scalar('owner_name')
            ->maxLength('owner_name', 100)
            ->requirePresence('owner_name', 'create')
            ->notEmptyString('owner_name');

        $validator
            ->scalar('establishment_name')
            ->maxLength('establishment_name', 150)
            ->requirePresence('establishment_name', 'create')
            ->notEmptyString('establishment_name');

        $validator
            ->scalar('address')
            ->requirePresence('address', 'create')
            ->notEmptyString('address');

        $validator
            ->scalar('type')
            ->requirePresence('type', 'create')
            ->notEmptyString('type');

        $validator
            ->scalar('risk_level')
            ->allowEmptyString('risk_level');

        $validator
            ->scalar('status')
            ->allowEmptyString('status');

        $validator
            ->dateTime('created_at')
            ->allowEmptyDateTime('created_at');

        return $validator;
    }
}
