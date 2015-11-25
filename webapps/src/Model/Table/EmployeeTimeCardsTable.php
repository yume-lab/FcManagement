<?php
namespace App\Model\Table;

use App\Model\Entity\EmployeeTimeCard;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;
use Cake\Log\Log;

/**
 * EmployeeTimeCards Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Stores
 * @property \Cake\ORM\Association\BelongsTo $Employees
 * @property \Cake\ORM\Association\BelongsTo $TimeCardStates
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
        $this->belongsTo('TimeCardStates', [
            'foreignKey' => 'current_state_id',
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
            ->add('hour_pay', 'valid', ['rule' => 'numeric'])
            ->requirePresence('hour_pay', 'create')
            ->notEmpty('hour_pay');

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
        $rules->add($rules->existsIn(['current_state_id'], 'TimeCardStates'));
        return $rules;
    }


    /**
     * 一月分の勤怠データを取得します.
     *
     * @param $storeId int 店舗ID
     * @param $employeeId int 従業員ID
     * @param $month int 取得する月 (YM形式)
     * @return array 取得したデータ
     */
    public function findMonthly($storeId, $employeeId, $month)
    {
        $records = $this->find()
            ->where(['store_id' => $storeId])
            ->where(['employee_id' => $employeeId])
            ->where(['worked_date LIKE ' => $month.'%'])
            ->toArray();

        $results = [];
        foreach ($records as $record) {
            $results[$record['worked_date']] = $record;
        }
        return $results;
    }

    /**
     * タイムカードデータへの書き込みを行います.
     *
     * @param $storeId int 店舗ID
     * @param $employeeId int 従業員ID
     * @param $path string 状態マスタのエイリアス
     * @param $time string 打刻時間
     * @return bool|\Cake\Datasource\EntityInterface
     */
    public function write($storeId, $employeeId, $path, $time)
    {
        $date = date('Ymd', strtotime($time));
        $entity = $this->find()
            ->where(['store_id' => $storeId])
            ->where(['employee_id' => $employeeId])
            ->where(['worked_date' => $date])
            ->first();
        if (empty($entity)) {
            $entity = $this->newEntity();
        }

        /** @var \App\Model\Table\TimeCardStatesTable $TimeCardStates */
        $TimeCardStates = TableRegistry::get('TimeCardStates');
        /** @var \App\Model\Table\EmployeeSalariesTable $EmployeeSalaries */
        $EmployeeSalaries = TableRegistry::get('EmployeeSalaries');

        $state = $TimeCardStates->findByPath($path)->first();

        $target = $this->getTimeColumn($path);
        $record = [
            "$target" => date('H:i:s', strtotime($time)),
            'store_id' => $storeId,
            'employee_id' => $employeeId,
            'current_state_id' => $state->id,
            'worked_date' => $date,
            'hour_pay' => $EmployeeSalaries->getAmount($storeId, $employeeId),
            'is_deleted' => false,
        ];
        $record = array_merge($record, $this->summary($entity, $record, $path));
        $entity = $this->patchEntity($entity, $record);
        return $this->save($entity);
    }

    /**
     * 全従業員の、当日の情報を取得します.
     *
     * @param $storeId
     * @return $this
     */
    public function findAllEmployees($storeId) {
        /** @var \App\Model\Table\EmployeesTable $Employees */
        $Employees = TableRegistry::get('Employees');
        return $Employees->find('all')
            ->hydrate(false)
            ->select([
                'EmployeeTimeCards.current_state_id'
            ])
            ->autoFields(true)
            ->join([
                'table' => 'employee_time_cards',
                'alias' => 'EmployeeTimeCards',
                'type' => 'LEFT',
                'conditions' => [
                    'EmployeeTimeCards.store_id = Employees.store_id',
                    'EmployeeTimeCards.employee_id = Employees.id',
                    'EmployeeTimeCards.worked_date' => date('Ymd')
                ],
            ])
            ->where([
                'Employees.store_id' => $storeId,
                'Employees.is_deleted' => false
            ]);
    }

    /**
     * 更新するカラム名を取得します.
     *
     * @param $path string 状態マスタのエイリアス
     * @return string 対象のカラム名
     */
    private function getTimeColumn($path)
    {
        $map = [
            '/start' => 'start_time',
            '/end' => 'end_time',
            '/break/start' => 'break_start_time',
            '/break/end' => 'break_end_time',
        ];
        return $map[$path];
    }

    /**
     * 累計時間とその日のサマリーデータを取得します.
     *
     * @param $entity \App\Model\Table\EmployeeTimeCardsTable 現在の自分自身のインスタンス
     * @param $path string 状態マスタのエイリアス
     * @return array
     */
    private function summary($entity, $record, $path) {

        $summaries = [
            'work_minute' => empty($entity->work_minute) ? 0 : $entity->work_minute,
            'break_minute' => empty($entity->break_minute) ? 0 : $entity->break_minute,
            'real_minute' => empty($entity->real_minute) ? 0 : $entity->real_minute,
            'total_work_minute' => empty($entity->total_work_minute) ? 0 : $entity->total_work_minute,
            'total_break_minute' => empty($entity->total_break_minute) ? 0 : $entity->total_break_minute,
            'total_real_minute' => empty($entity->total_real_minute) ? 0 : $entity->total_real_minute,
        ];

        switch ($path) {
            case '/start':
            case '/break/start':
                break;
            case '/break/end':
                $summaries['break_minute'] = $this->diffMinutes($entity->break_start_time, $record['break_end_time']);
                $summaries['total_break_minute'] = $entity->total_break_minute + $summaries['break_minute'];
                break;
            case '/end':
                $summaries['work_minute'] = $this->diffMinutes($entity->start_time, $record['end_time']);
                $summaries['total_work_minute'] = $entity->total_work_minute + $summaries['work_minute'];
                $summaries['real_minute'] = $summaries['work_minute'] - $summaries['break_minute'];
                $summaries['total_real_minute'] = $entity->total_real_minute + $summaries['real_minute'];
                break;
        }
        return $summaries;
    }

    /**
     * 時間の差を分で取得します.
     *
     * @param $start string 対象の開始時間 (H:i:s)
     * @param $end string 対象の終了時間 (H:i:s)
     * @return int 時間(分)
     */
    private function diffMinutes($start, $end) {
        return (strtotime($end) - strtotime($start)) / 60;
    }
}
