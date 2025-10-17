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
            $client = $this->get($clientId);
            $type = strtolower($client->type);
            \Cake\Log\Log::info("✅ Client found: #{$client->id} - {$client->establishment_name}, Type: {$type}");

            $coverage = [
                'general' => ['residential', 'commercial'],
                'mechanical' => ['industrial', 'storage'],
                'electrical' => ['residential', 'commercial', 'industrial', 'storage', 'assembly', 'miscellaneous'],
                'structural' => ['commercial', 'assembly'],
                'hazardous' => ['industrial', 'storage', 'miscellaneous']
            ];

            $inspectorsTable = TableRegistry::getTableLocator()->get('Inspectors');
            $availabilitiesTable = TableRegistry::getTableLocator()->get('Availabilities');

            // 3. Find eligible inspectors
            $eligibleInspectors = $inspectorsTable->find()
                ->where(['status' => 'available'])
                ->toArray();

            $candidates = [];
            foreach ($eligibleInspectors as $inspector) {
                if (isset($coverage[$inspector->specialization]) && in_array($type, $coverage[$inspector->specialization])) {
                    $candidates[] = $inspector;
                    \Cake\Log\Log::info("🎯 Eligible inspector: #{$inspector->id} - {$inspector->specialization}");
                }
            }

            if (empty($candidates)) {
                \Cake\Log\Log::warning("❌ No eligible inspectors found for client type: {$type}");
                return false;
            }

            // 4. Try to schedule inspection in the earliest available slot respecting max 2 per day
            $selectedInspector = null;
            $selectedAvailability = null;

            foreach ($candidates as $inspector) {
                // Get available slots from today onwards
                $slots = $availabilitiesTable->find()
                    ->where([
                        'inspector_id' => $inspector->id,
                        'is_available' => true,
                        'available_date >=' => FrozenDate::today()
                    ])
                    ->order(['available_date' => 'ASC'])
                    ->toArray();

                foreach ($slots as $slot) {
                    $date = $slot->available_date;

                    // Count inspections for this inspector on this specific date
                    $inspectionsOnDate = $inspectionsTable->find()
                        ->where([
                            'inspector_id' => $inspector->id,
                            'scheduled_date' => $date
                        ])
                        ->count();

                    \Cake\Log\Log::info("Inspector #{$inspector->id} has {$inspectionsOnDate}/2 inspections on {$date}");

                    if ($inspectionsOnDate < 2) {
                        $selectedInspector = $inspector;
                        $selectedAvailability = $slot;
                        \Cake\Log\Log::info("✅ Found slot for inspector #{$inspector->id} on {$date} ({$inspectionsOnDate}/2 inspections)");
                        break 2; // Break both loops
                    }
                }
            }

            if (!$selectedInspector || !$selectedAvailability) {
                \Cake\Log\Log::warning("❌ No available dates found within capacity limits");
                return false;
            }

            // 5. Reserve the availability
            $selectedAvailability->is_available = false;
            $selectedAvailability->reason = 'Auto-assigned for inspection';
            $availabilitiesTable->save($selectedAvailability);

            // 6. Create the inspection
            $inspectionData = [
                'client_id' => $clientId,
                'inspector_id' => $selectedInspector->id,
                'scheduled_date' => $selectedAvailability->available_date,
                'status' => 'scheduled'
            ];

            \Cake\Log\Log::info("📝 Creating inspection with data: " . json_encode($inspectionData));

            $inspection = $inspectionsTable->newEntity($inspectionData);

            $result = $inspectionsTable->save($inspection);

            if ($result) {
                \Cake\Log\Log::info("🎉 SUCCESS! Inspection #{$inspection->id} created for {$selectedAvailability->available_date}");
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
