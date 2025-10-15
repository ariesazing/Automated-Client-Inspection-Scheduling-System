<?php

declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use Cake\I18n\FrozenDate;


/**
 * Availabilities Model
 *
 * @property \App\Model\Table\InspectorsTable&\Cake\ORM\Association\BelongsTo $Inspectors
 *
 * @method \App\Model\Entity\Availability newEmptyEntity()
 * @method \App\Model\Entity\Availability newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Availability[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Availability get($primaryKey, $options = [])
 * @method \App\Model\Entity\Availability findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Availability patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Availability[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Availability|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Availability saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Availability[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Availability[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Availability[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Availability[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class AvailabilitiesTable extends Table
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

        $this->setTable('availabilities');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Inspectors', [
            'foreignKey' => 'inspector_id',
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
            ->integer('inspector_id')
            ->notEmptyString('inspector_id');

        $validator
            ->date('available_date')
            ->requirePresence('available_date', 'create')
            ->notEmptyDate('available_date');

        $validator
            ->boolean('is_available')
            ->allowEmptyString('is_available');

        $validator
            ->scalar('reason')
            ->requirePresence('reason', 'create')
            ->notEmptyString('reason');

        return $validator;
    }

    public function maintainAvailabilityWindow()
    {

        $inspectorsTable = TableRegistry::getTableLocator()->get('Inspectors');
        $inspectors = $inspectorsTable->find('all')->toArray();

        $today = FrozenDate::today();
        $endDate = $today->addMonth();
        $weekdays = [1, 2, 3, 4, 5]; // Monday to Friday

        foreach ($inspectors as $inspector) {
            $existingDates = $this->find()
                ->select(['available_date'])
                ->where([
                    'inspector_id' => $inspector->id,
                    'available_date >=' => $today,
                    'available_date <=' => $endDate
                ])
                ->extract('available_date')
                ->map(fn($d) => $d->format('Y-m-d'))
                ->toArray();

            // First-time seeding: no records in the next month
            if (empty($existingDates)) {
                for ($date = $today; $date <= $endDate; $date = $date->addDay()) {
                    if (in_array($date->dayOfWeek, $weekdays)) {
                        $this->save($this->newEntity([
                            'inspector_id' => $inspector->id,
                            'available_date' => $date,
                            'is_available' => 1,
                            'reason' => 'Initial auto-generated availability'
                        ]));
                    }
                }
            } else {
                // Daily maintenance: add today's weekday if missing
                if (
                    in_array($today->dayOfWeek, $weekdays) &&
                    !in_array($today->format('Y-m-d'), $existingDates)
                ) {
                    $this->save($this->newEntity([
                        'inspector_id' => $inspector->id,
                        'available_date' => $today,
                        'is_available' => 1,
                        'reason' => 'Daily auto-generated availability'
                    ]));
                }
            }

            // 🧹 Delete past availabilities
            $this->deleteAll([
                'inspector_id' => $inspector->id,
                'DATE(available_date) <' => $today->format('Y-m-d')
            ]);
            // 🔄 Update inspector status based on today's availability
            $todayAvailability = $this->find()
                ->where([
                    'inspector_id' => $inspector->id,
                    'available_date' => $today
                ])
                ->first();

            if ($todayAvailability && $todayAvailability->is_available === false) {
                $inspector->status = 'on_leave';
            } else {
                $inspector->status = 'available'; // or 'active' or whatever your default is
            }

            $inspectorsTable->save($inspector);
        }
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
        $rules->add($rules->existsIn('inspector_id', 'Inspectors'), ['errorField' => 'inspector_id']);

        return $rules;
    }
}
