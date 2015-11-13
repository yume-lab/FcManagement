<?php
namespace App\Model\Table;

use App\Model\Entity\TimeCard;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;

/**
 * TimeCards Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Stores
 * @property \Cake\ORM\Association\BelongsTo $Employees
 */
class TimeCardsTable extends Table
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

        $this->table('time_cards');
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
            ->add('target_ym', 'valid', ['rule' => 'numeric'])
            ->requirePresence('target_ym', 'create')
            ->notEmpty('target_ym');

        $validator
            ->allowEmpty('body');

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

    /**
     * 勤務表データへの書き込みを行います
     *
     * @param $employeeId 従業員ID
     * @param $storeId 店舗ID
     * @param $stateId 状態ID
     * @param $time 打刻時間
     * @return bool|\Cake\Datasource\EntityInterface
     */
    public function write($employeeId, $storeId, $stateId, $time)
    {
        $targetYm = date('Ym', strtotime($time));
        $day = date('d', strtotime($time));

        $entity = $this->find()
            ->where(['store_id' => $storeId])
            ->where(['employee_id' => $employeeId])
            ->where(['target_ym' => $targetYm])
            ->first();
        if (empty($entity)) {
            $entity = $this->newEntity();
        }

        /*
         * こんな感じの配列を組む
         * {
         *  "day-13":[
         *      {"state_id":3,"time":"2015-11-13 09:29:05"},
         *      {"state_id":2,"time":"2015-11-13 09:29:16"}
         *  ],
         *  "day-15":[
         *      {"state_id":2,"time":"2015-11-15 12:30:45"}
         *  ]
         * }
         */
        $TimeCardStates = TableRegistry::get('TimeCardStates');
        $state = $TimeCardStates->get($stateId);

        $body = empty($entity->body) ? [] : json_decode($entity->body);
        $body = (array) $body;
        $dayKey = 'day-'.$day;
        if (!isset($body[$dayKey])) {
            $body[$dayKey] = [];
        }

        $body[$dayKey] = (array) $body[$dayKey];
        $body[$dayKey][] = [
            'state_id' => $state->id,
            'alias' => $state->alias,
            'time' => date('Y-m-d H:i:s', strtotime($time))
        ];

        $record = [
            'store_id' => $storeId,
            'employee_id' => $employeeId,
            'target_ym' => $targetYm,
            'body' => json_encode($body),
            'is_deleted' => false
        ];

        $data = $this->patchEntity($entity, $record);
        return $this->save($data);
    }
}
