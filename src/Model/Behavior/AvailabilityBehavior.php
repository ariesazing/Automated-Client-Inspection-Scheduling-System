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
        $inspectionsTable = TableRegistry::getTableLocator()->get('Inspections');
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
                        'reason' => 'Auto-generated'
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
                ? 'unavailable'
                : 'available';

            $inspectorsTable->save($inspector);
            
            //Update is_available = true if no matching inspection exists for that date
            $inspectionsGrouped = $inspectionsTable->find()
                ->where([
                    'inspector_id' => $inspector->id,
                    'scheduled_date IS NOT' => null
                ])
                ->all()
                ->groupBy(function ($i) {
                    return $i->scheduled_date instanceof \DateTimeInterface
                        ? $i->scheduled_date->format('Y-m-d')
                        : 'unscheduled';
                });

            $inspectionsByDate = $inspectionsGrouped->toArray();

            $availabilities = $availabilitiesTable->find()
                ->where(['inspector_id' => $inspector->id])
                ->all();

            foreach ($availabilities as $availability) {
                $scheduledDate = $availability->available_date->format('Y-m-d');

                $hasMatchingInspection = isset($inspectionsByDate[$scheduledDate]);

                if (!$hasMatchingInspection) {
                    $availability->is_available = true;
                    $availability->reason = 'Auto-generated to maintain 22-day window';
                    $availabilitiesTable->save($availability);
                }
            }
        }
    }
}
