<?php

declare(strict_types=1);

namespace App\Model\Behavior;

use Cake\ORM\Behavior;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\I18n\FrozenDate;
use Cake\Log\Log;

class AutoScheduleBehavior extends Behavior
{
    protected $_defaultConfig = [
        'inspectionsTable' => 'Inspections',
        'inspectorsTable' => 'Inspectors', 
        'availabilitiesTable' => 'Availabilities',
        'maxInspectionsPerDay' => 2
    ];

    public function autoCreateInspection(int $clientId): bool
    {
        $inspectionsTable = TableRegistry::getTableLocator()->get($this->getConfig('inspectionsTable'));
        
        Log::info("🚨 STARTING AUTO-CREATION FOR CLIENT #{$clientId}");

        try {
            // Check if inspection already exists
            $existing = $inspectionsTable->find()
                ->where(['client_id' => $clientId])
                ->first();
                
            if ($existing) {
                Log::info("✅ Inspection already exists: #{$existing->id}");
                return true;
            }

            // Get client data
            $client = $this->table()->get($clientId);
            $type = strtolower($client->type);
            Log::info("✅ Client found: #{$client->id} - {$client->establishment_name}, Type: {$type}");

            // Find eligible inspectors
            $coverage = [
                'general' => ['residential', 'commercial'],
                'mechanical' => ['industrial', 'storage'],
                'electrical' => ['residential', 'commercial', 'industrial', 'storage', 'assembly', 'miscellaneous'],
                'structural' => ['commercial', 'assembly'],
                'hazardous' => ['industrial', 'storage', 'miscellaneous']
            ];

            $inspectorsTable = TableRegistry::getTableLocator()->get($this->getConfig('inspectorsTable'));
            $availabilitiesTable = TableRegistry::getTableLocator()->get($this->getConfig('availabilitiesTable'));

            $eligibleInspectors = $inspectorsTable->find()
                ->where(['status' => 'available'])
                ->toArray();

            $candidates = [];
            foreach ($eligibleInspectors as $inspector) {
                if (isset($coverage[$inspector->specialization]) && 
                    in_array($type, $coverage[$inspector->specialization])) {
                    $candidates[] = $inspector;
                    Log::info("🎯 Eligible inspector: #{$inspector->id} - {$inspector->specialization}");
                }
            }

            if (empty($candidates)) {
                Log::warning("❌ No eligible inspectors found for client type: {$type}");
                return false;
            }

            // Find available slot
            $selectedInspector = null;
            $selectedAvailability = null;

            foreach ($candidates as $inspector) {
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
                    
                    $inspectionsOnDate = $inspectionsTable->find()
                        ->where([
                            'inspector_id' => $inspector->id,
                            'scheduled_date' => $date
                        ])
                        ->count();

                    Log::info("Inspector #{$inspector->id} has {$inspectionsOnDate}/2 inspections on {$date}");

                    if ($inspectionsOnDate < $this->getConfig('maxInspectionsPerDay')) {
                        $selectedInspector = $inspector;
                        $selectedAvailability = $slot;
                        Log::info("✅ Found slot for inspector #{$inspector->id} on {$date} ({$inspectionsOnDate}/2 inspections)");
                        break 2;
                    }
                }
            }

            if (!$selectedInspector || !$selectedAvailability) {
                Log::warning("❌ No available dates found within capacity limits");
                return false;
            }

            // Reserve availability and create inspection
            $selectedAvailability->is_available = false;
            $selectedAvailability->reason = 'Auto-assigned for inspection';
            $availabilitiesTable->save($selectedAvailability);

            $inspectionData = [
                'client_id' => $clientId,
                'inspector_id' => $selectedInspector->id,
                'scheduled_date' => $selectedAvailability->available_date,
                'status' => 'scheduled'
            ];

            Log::info("📝 Creating inspection with data: " . json_encode($inspectionData));
            
            $inspection = $inspectionsTable->newEntity($inspectionData);
            $result = $inspectionsTable->save($inspection);
            
            if ($result) {
                Log::info("🎉 SUCCESS! Inspection #{$inspection->id} created for {$selectedAvailability->available_date}");
                return true;
            } else {
                Log::error("💥 FAILED! Could not save inspection");
                return false;
            }
            
        } catch (\Exception $e) {
            Log::error("💀 EXCEPTION: " . $e->getMessage());
            return false;
        }
    }

    public function queueInspectionCreation(int $clientId)
    {
        $queueFile = LOGS . 'inspection_queue.txt';
        file_put_contents($queueFile, $clientId . PHP_EOL, FILE_APPEND | LOCK_EX);
        register_shutdown_function([$this, 'processInspectionQueue']);
    }

    public function processInspectionQueue()
    {
        $queueFile = LOGS . 'inspection_queue.txt';

        if (!file_exists($queueFile)) {
            return;
        }

        $content = file_get_contents($queueFile);
        $clientIds = array_filter(explode(PHP_EOL, $content));

        if (empty($clientIds)) {
            return;
        }

        file_put_contents($queueFile, '');

        foreach ($clientIds as $clientId) {
            if (!empty($clientId)) {
                Log::info("Processing queued inspection for client #{$clientId}");
                $this->autoCreateInspection((int)$clientId);
            }
        }
    }
}