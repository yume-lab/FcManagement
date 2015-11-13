<?php
namespace App\Model\Table;

use App\Model\Entity\LatestTimeCard;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * LatestTimeCards Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Stores
 * @property \Cake\ORM\Association\BelongsTo $Employees
 * @property \Cake\ORM\Association\BelongsTo $TimeCardStates
 */
class LatestTimeCardsTable extends Table
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

        $this->table('latest_time_cards');
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
        $this->belongsTo('TimeCardStates', [
            'foreignKey' => 'time_card_state_id',
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
        $rules->add($rules->existsIn(['time_card_state_id'], 'TimeCardStates'));
        return $rules;
    }

    /**
     * 最新打刻情報の書き込みを行います.
     *
     * @param $employeeId 従業員ID
     * @param $storeId 店舗ID
     * @param $stateId 状態ID
     * @see TimeCardStatesTable
     * @return bool|\Cake\Datasource\EntityInterface 処理結果
     */
    public function write($employeeId, $storeId, $stateId) {
        $entity = $this->find()
            ->where(['store_id' => $storeId])
            ->where(['employee_id' => $employeeId])
            ->first();
        if (empty($entity)) {
            $entity = $this->newEntity();
        }

        $record = [
            'store_id' => $storeId,
            'employee_id' => $employeeId,
            'time_card_state_id' => $stateId
        ];

        $data = $this->patchEntity($entity, $record);
        return $this->save($data);
    }
}
