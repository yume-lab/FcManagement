<?php
namespace App\Model\Table;

use App\Model\Entity\EmployeeTimeCard;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * EmployeeTimeCards Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Stores
 * @property \Cake\ORM\Association\BelongsTo $Employees
 */
class EmployeeTimeCardsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('employee_time_cards');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Stores', [
            'foreignKey' => 'store_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Employees', [
            'foreignKey' => 'employee_id',
            'joinType' => 'INNER'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->add('id', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('id', 'create');

        $validator
            ->requirePresence('worked_date', 'create')
            ->notEmpty('worked_date');

        $validator
            ->add('start_time', 'valid', ['rule' => 'time'])
            ->allowEmpty('start_time');

        $validator
            ->add('end_time', 'valid', ['rule' => 'time'])
            ->allowEmpty('end_time');

        $validator
            ->add('break_start_time', 'valid', ['rule' => 'time'])
            ->allowEmpty('break_start_time');

        $validator
            ->add('break_end_time', 'valid', ['rule' => 'time'])
            ->allowEmpty('break_end_time');

        $validator
            ->add('work_minute', 'valid', ['rule' => 'numeric'])
            ->requirePresence('work_minute', 'create')
            ->notEmpty('work_minute');

        $validator
            ->add('break_minute', 'valid', ['rule' => 'numeric'])
            ->requirePresence('break_minute', 'create')
            ->notEmpty('break_minute');

        $validator
            ->add('real_minute', 'valid', ['rule' => 'numeric'])
            ->requirePresence('real_minute', 'create')
            ->notEmpty('real_minute');

        $validator
            ->add('total_work_minute', 'valid', ['rule' => 'numeric'])
            ->requirePresence('total_work_minute', 'create')
            ->notEmpty('total_work_minute');

        $validator
            ->add('total_break_minute', 'valid', ['rule' => 'numeric'])
            ->requirePresence('total_break_minute', 'create')
            ->notEmpty('total_break_minute');

        $validator
            ->add('total_real_minute', 'valid', ['rule' => 'numeric'])
            ->requirePresence('total_real_minute', 'create')
            ->notEmpty('total_real_minute');

        $validator
            ->add('amount', 'valid', ['rule' => 'numeric'])
            ->requirePresence('amount', 'create')
            ->notEmpty('amount');

        $validator
            ->add('is_deleted', 'valid', ['rule' => 'boolean'])
            ->requirePresence('is_deleted', 'create')
            ->notEmpty('is_deleted');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['store_id'], 'Stores'));
        $rules->add($rules->existsIn(['employee_id'], 'Employees'));
        return $rules;
    }
}
