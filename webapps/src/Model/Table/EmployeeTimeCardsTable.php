<?php
namespace App\Model\Table;

use App\Model\Entity\EmployeeTimeCard;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;

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
            ->add('round_start_time', 'valid', ['rule' => 'time'])
            ->allowEmpty('round_start_time');

        $validator
            ->add('round_end_time', 'valid', ['rule' => 'time'])
            ->allowEmpty('round_end_time');

        $validator
            ->add('worked_minutes', 'valid', ['rule' => 'numeric'])
            ->requirePresence('worked_minutes', 'create')
            ->notEmpty('worked_minutes');

        $validator
            ->add('rested_minutes', 'valid', ['rule' => 'numeric'])
            ->requirePresence('rested_minutes', 'create')
            ->notEmpty('rested_minutes');

        $validator
            ->add('real_worked_minutes', 'valid', ['rule' => 'numeric'])
            ->requirePresence('real_worked_minutes', 'create')
            ->notEmpty('real_worked_minutes');

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
     * @param $additions array その他、直接登録をしたい項目.
     *  - key: カラム名
     *  - value: 値
     * @return bool|\Cake\Datasource\EntityInterface
     */
    public function write($storeId, $employeeId, $path, $time, $additions = [])
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

        $data = array_merge($entity->toArray(), [
            'store_id' => $storeId,
            'employee_id' => $employeeId,
            'current_state_id' => $state->id,
            'worked_date' => $date,
            'hour_pay' => $EmployeeSalaries->getAmount($storeId, $employeeId),
            'is_deleted' => false,
        ]);
        $data = array_merge($data, $additions);
        $data = array_merge($data, $this->getTime($time, $path));
        $data = array_merge($data, $this->summary($data));
        $entity = $this->patchEntity($entity, $data);
        return $this->save($entity);
    }

    /**
     * 打刻時間の修正処理を行います.
     *
     * @param $storeId int 店舗ID
     * @param $employeeId int 従業員ID
     * @param $workedDate string 対象日 (Ymd形式)
     * @param $values array 修正された時間の値. TimeCardStates.pathがキー.
     *  - round_start_time
     *  - round_end_time
     *  - hour_pay
     *  - rested_minutes
     * @return bool|\Cake\Datasource\EntityInterface
     */
    public function patch($storeId, $employeeId, $workedDate, $values)
    {
        $entity = $this->find()
            ->where(['store_id' => $storeId])
            ->where(['employee_id' => $employeeId])
            ->where(['worked_date' => $workedDate])
            ->first();
        if (empty($entity)) {
            $entity = $this->newEntity();
        }

        /** @var \App\Model\Table\TimeCardStatesTable $TimeCardStates */
        $TimeCardStates = TableRegistry::get('TimeCardStates');

        $state = $TimeCardStates->findByPath('/end')->first();

        $data = array_merge($entity->toArray(), [
            'store_id' => $storeId,
            'employee_id' => $employeeId,
            'current_state_id' => $state->id,
            'worked_date' => $workedDate,
            'is_deleted' => false,
        ]);
        $data = array_merge($data, $values);
        $data = array_merge($data, $this->summary($data));
        $entity = $this->patchEntity($entity, $data);
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
     * 勤怠打刻データの取得を行います.
     *
     * @param $date string 時間
     * @param $path string 対象時間のエイリアス
     * @return array 更新するカラムの配列
     */
    private function getTime($date, $path)
    {
        $results = [];
        $time = date('H:i:s', strtotime($date));

        if ($path == '/start') {
            $results['start_time'] = $time;
            $results['round_start_time'] = $this->roundTime($time, $path);
        }
        if ($path == '/end') {
            $results['end_time'] = $time;
            $results['round_end_time'] = $this->roundTime($time, $path);
        }

        return $results;
    }

    /**
     * 時間を丸める処理を行います.
     *
     * @param $time string 対象の時間 (H:i:s形式)
     * @param $path string 対象時間のエイリアス
     * @return string 丸めた時間
     */
    private function roundTime($time, $path) {

        $interval = 15;
        $split = explode(':', $time);
        $min = $split[1];
        $round = $min;
        if ($path == '/start') {
            if (!($min == 0 || ($min % $interval) == 0)) {
                // 0もしくは区切りの数値で割れない場合だけ繰り上げ処理を行う
                $round = ((int) ($min / $interval) + 1) * $interval;
            }

            if ($round == 60) {
                $split[0] = $split[0] + 1;
                $round = 0;
            }
        }

        if ($path == '/end') {
            if (!($min == 0 || ($min % $interval) == 0)) {
                $round = (int) ($min / $interval) * $interval;
            }
        }
        // 丸めるので、秒は0に
        $split[1] = $round;
        $split[2] = '00';

        return implode(':', $split);
    }

    /**
     * その日のサマリーデータを取得します.
     *
     * @param $data array レコードのデータ
     * @return array
     */
    private function summary($data)
    {
        $summaries = [
            'worked_minutes' => empty($data['worked_minutes']) ? 0 : $data['worked_minutes'],
            'rested_minutes' => empty($data['rested_minutes']) ? 0 : $data['rested_minutes'],
            'real_worked_minutes' => empty($data['real_worked_minutes']) ? 0 : $data['real_worked_minutes']
        ];

        if (isset($data['round_end_time'])) {
            $summaries['worked_minutes'] = $this->diffMinutes($data['round_start_time'], $data['round_end_time']);
            $summaries['real_worked_minutes'] = $summaries['worked_minutes'] - $summaries['rested_minutes'];
        }
        if (!empty($summaries['worked_minutes'])) {
            $summaries['real_worked_minutes'] = $summaries['worked_minutes'] - $summaries['rested_minutes'];
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
