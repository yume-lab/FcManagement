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
     * API
     * 勤怠データの更新処理を行います.
     * TODO: 実装
     */
    public function update()
    {
        $this->autoRender = false;

        $data = $this->request->data();
        $this->log($data);

        $ymd = $data['target'];
        $employeeId = $data['employeeId'];
        $input = $data['data'];

        $entity = $this->TimeCards->find()
            ->where(['store_id' => parent::getCurrentStoreId()])
            ->where(['employee_id' => $employeeId])
            ->where(['target_ym' => date('Ym', strtotime($ymd))])
            ->first();

        $body = json_decode($entity->body, true);

        $this->log($entity);
        $this->log($body);


        echo json_encode(['success' => 'success']);
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
            $matrix[$day] = $this->buildMatrixForDaily($data);
        }
        return $matrix;
    }

    /**
     * 日毎のデータ整形を行います.
     * @param $daily array 日のデータ
     * @return array 整形したデータ
     */
    private function buildMatrixForDaily($daily)
    {
        $results = [];
        foreach ($daily as $data) {
            // 日の中の種別のループ
            $this->log($data);
            $time = date('H:i', strtotime($data->time));
            $results[$data->alias] = $time;
        }
        $results['/all'] = $this->TimeCard->getAll($results);
        $results['/break_all'] = $this->TimeCard->getBreak($results);
        $results['/real'] = $this->TimeCard->getReal($results);
        $this->log($results);
        return $results;
    }

}
