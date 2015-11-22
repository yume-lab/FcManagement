<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;

/**
 * TimeCards Controller
 * 勤怠一覧コントローラー.
 *
 * @property \App\Model\Table\TimeCardsTable $TimeCards
 * @property \App\Model\Table\EmployeesTable $Employees
 * @property \App\Controller\Component\TimeCardComponent $TimeCard
 */
class TimeCardsController extends AppController
{

    /**
     * 使用ヘルパー
     * @var array
     */
    public $helpers = ['TimeCard'];

    /**
     * 使用コンポーネント
     * @var array
     */
    public $components = ['TimeCard'];


    /**
     * 初期表示.
     *
     * @return void
     */
    public function index()
    {
        $Employees = TableRegistry::get('Employees');
        $employees = $Employees->findByStoreId(parent::getCurrentStoreId());
        $this->set(compact('employees'));
    }

    /**
     * 勤務表テーブルの表示を行います.
     */
    public function table()
    {
        $employeeId = $this->request->query('employeeId');
        $targetYm = $this->request->query('target_ym');

        $results = $this->TimeCards->find()
            ->where(['store_id' => parent::getCurrentStoreId()])
            ->where(['employee_id' => $employeeId])
            ->where(['target_ym' => $targetYm])
            ->first();

        $Employees = TableRegistry::get('Employees');
        $employee = $Employees->get($employeeId);

        $matrix = $this->buildMatrix($results);

        $target = strtotime($targetYm.'01');
        $showMonth = date('Y年m月', $target);
        $current = date('Y-m-d', $target);
        $next = date('Ym', strtotime(date('Y-m-1', $target). ' +1 month'));
        $prev = date('Ym', strtotime(date('Y-m-1', $target). ' -1 month'));

        $this->log($matrix);
        $this->set(compact('matrix', 'employee', 'showMonth', 'next', 'prev', 'current'));
    }

    /**
     * 表示用データの整形を行います.
     *
     * @param $record object 勤務表データの取得結果
     * @return array 表示用配列
     */
    private function buildMatrix($record)
    {
        if (empty($record)) {
            return [];
        }
        $body = json_decode($record->body);
        $matrix = [];
        foreach ($body as $day => $data) {
            // 日別のループ
            $this->log($day);
            $this->log($data);

            $tmp = [];
            foreach ($data as $d) {
                // 日の中の種別のループ
                $this->log($d);
                $time = date('H:i', strtotime($d->time));
                $tmp[$d->alias] = $time;
            }
            $tmp['/all'] = $this->TimeCard->getAll($tmp);
            $tmp['/break_all'] = $this->TimeCard->getBreak($tmp);
            $tmp['/real'] = $this->TimeCard->getReal($tmp);
            $this->log($tmp);

            $matrix[$day] = $tmp;
        }
        return $matrix;
    }

}
