<?php

declare(strict_types=1);

namespace App\Model\Behavior;

use Cake\ORM\Behavior;
use Cake\ORM\TableRegistry;
use Cake\I18n\FrozenDate;


class AvailabilityBehavior extends Behavior
{
    protected $_defaultConfig = [
        'inspectorsTable' => 'Inspectors',
        'weekdays' => [1, 2, 3, 4, 5], // Monday to Friday
        'windowDays' => 22
    ];

    public function maintainAvailabilityWindow()
    {
        $config = $this->getConfig();
        $availabilitiesTable = TableRegistry::getTableLocator()->get('Availabilities');
        
        $inspectorsTable = TableRegistry::getTableLocator()->get($config['inspectorsTable']);
        $inspectors = $inspectorsTable->find('all')->toArray();

        $today = FrozenDate::today();

        foreach ($inspectors as $inspector) {
            // 🧹 Delete past availabilities
            $availabilitiesTable->deleteAll([
                'inspector_id' => $inspector->id,
                'available_date <' => $today
            ]);

            // Get existing future weekday availabilities (any status)
            $existingDates = $availabilitiesTable->find()
                ->select(['available_date'])
                ->where([
                    'inspector_id' => $inspector->id,
                    'available_date >=' => $today
                ])
                ->extract('available_date')
                ->map(fn($d) => $d->format('Y-m-d'))
                ->toArray();

            // Count how many weekday records already exist
            $existingWeekdays = array_filter($existingDates, function ($date) use ($config) {
                return in_array((new FrozenDate($date))->dayOfWeek, $config['weekdays']);
            });

            $needed = $config['windowDays'] - count($existingWeekdays);

            // Add missing weekday records
            $date = $today;
            while ($needed > 0) {
                $formatted = $date->format('Y-m-d');
                if (in_array($date->dayOfWeek, $config['weekdays']) && !in_array($formatted, $existingDates)) {
                    $availabilitiesTable->save($availabilitiesTable->newEntity([
                        'inspector_id' => $inspector->id,
                        'available_date' => $date,
                        'is_available' => 1,
                        'reason' => 'Auto-generateds'
                    ]));
                    $needed--;
                }
                $date = $date->addDay();
            }

            // Update inspector status based on today's availability
            $todayAvailability = $availabilitiesTable->find()
                ->where([
                    'inspector_id' => $inspector->id,
                    'available_date' => $today
                ])
                ->first();

            $inspector->status = ($todayAvailability && $todayAvailability->is_available === false)
                ? 'on_leave'
                : 'available';

            $inspectorsTable->save($inspector);
        }
    }
}