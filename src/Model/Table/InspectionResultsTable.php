<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * InspectionResults Model
 *
 * @property \App\Model\Table\InspectionsTable&\Cake\ORM\Association\BelongsTo $Inspections
 *
 * @method \App\Model\Entity\InspectionResult newEmptyEntity()
 * @method \App\Model\Entity\InspectionResult newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\InspectionResult[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\InspectionResult get($primaryKey, $options = [])
 * @method \App\Model\Entity\InspectionResult findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\InspectionResult patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\InspectionResult[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\InspectionResult|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\InspectionResult saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\InspectionResult[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\InspectionResult[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\InspectionResult[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\InspectionResult[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class InspectionResultsTable extends Table
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

        $this->setTable('inspection_results');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Inspections', [
            'foreignKey' => 'inspection_id',
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
            ->integer('inspection_id')
            ->notEmptyString('inspection_id');

        $validator
            ->scalar('result')
            ->allowEmptyString('result');

        $validator
            ->scalar('findings')
            ->allowEmptyString('findings');

        $validator
            ->scalar('recommendations')
            ->allowEmptyString('recommendations');

        $validator
            ->integer('encoded_by')
            ->allowEmptyString('encoded_by');

        $validator
            ->dateTime('created_at')
            ->allowEmptyDateTime('created_at');

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
        $rules->add($rules->existsIn('inspection_id', 'Inspections'), ['errorField' => 'inspection_id']);

        return $rules;
    }
}
