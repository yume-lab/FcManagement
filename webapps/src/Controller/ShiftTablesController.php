<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;

/**
 * ShiftTables Controller
 * シフト作成コントローラ
 *
 * @property \App\Model\Table\ShiftTablesTable $ShiftTables
 * @property \App\Model\Table\EmployeesTable $Employees
 */
class ShiftTablesController extends AppController
{

    /**
     * 初期表示
     *
     * @return void
     */
    public function index()
    {
        $Employees = TableRegistry::get('Employees');

        $store = $this->UserAuth->store();
        $opened = date('H:i:s', strtotime($store->opened));
        $closed = date('H:i:s', strtotime($store->closed));

        $this->set('opened', $opened);
        $this->set('closed', $closed);
        $this->set('times', $this->buildSelectableTime($opened, $closed));
        $this->set('employees', $Employees->find()->where(['Employees.is_deleted' => false]));
        $this->set('_serialize', ['employees']);
    }

    /**
     * API
     * シフトデータ取得
     * 下記のGETパラメータを受け取ります.
     * start - 取得開始日
     * end - 取得終了日
     */
    public function get()
    {
        $this->autoRender = false;

        $query = $this->request->query;
        $this->log($query);

        $start = date('Ym', strtotime($query['start']));
        $end = date('Ym', strtotime($query['end']));

        $shifts = $this->ShiftTables->find()
            ->where(['store_id' => parent::getCurrentStoreId()])
            ->where(['target_ym >= ' => $start])
            ->where(['target_ym < ' => $end])
            ->all();

        $response = [];
        foreach ($shifts as $shift) {
            $data = json_decode($shift->body);
            $response = array_merge($response, $data);
        }
        $this->log($response);

        echo json_encode($response);
    }

    /**
     * API
     * シフトの一時保存を行います.
     *
     * year - 登録する年
     * month - 登録する月
     * shift - シフトデータ配列
     *
     * @return \Cake\Network\Response|void
     */
    public function update()
    {
        $this->autoRender = false;

        $data = $this->request->data();
        $this->log($data);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $targetYm = $data['year'].$data['month'];
            $shift = $data['shift'];
            $shiftTable = $this->ShiftTables->find()
                ->where(['store_id' => parent::getCurrentStoreId()])
                ->where(['target_ym' => $targetYm])
                ->first();
            if (empty($shiftTable)) {
                $shiftTable = $this->ShiftTables->newEntity();
            }
            $record = [
                'store_id' => parent::getCurrentStoreId(),
                'target_ym' => $targetYm,
                'body' => json_encode($this->buildBody($shift)),
                'is_deleted' => false
            ];

            $shiftTable = $this->ShiftTables->patchEntity($shiftTable, $record);
            $isSuccess = ($this->ShiftTables->save($shiftTable));
            echo json_encode(['success' => $isSuccess]);
        }
    }

    /**
     * シフトデータを、保存用に整形します.
     * @param $data array パラメータのシフトデータ
     * @return array 整形した配列
     */
    private function buildBody($data)
    {
        $useKeys = ['_id', 'title', 'employeeId', 'start', 'end'];

        $results = [];
        foreach ($data as $shift) {
            $tmp = [];
            foreach ($useKeys as $key) {
                $tmp[$key] = $shift[$key];
            }
            $results[] = $tmp;
        }
        return $results;
    }

    /**
     * シフト作成時に選択可能な時間帯を配列で構築します.
     *
     * @param $opened string 営業開始時間
     * @param $closed string 営業終了時間
     * @return array 選択可能な時間帯の配列
     */
    private function buildSelectableTime($opened, $closed)
    {
        $begin = date('H', strtotime($opened));
        $end = date('H', strtotime($closed));

        $results = [];
        // TODO: カッコ悪い
        // TODO: 何分刻みかは、店舗設定で動的にできるようにする
        for ($hour = $begin; $hour <= $end; $hour++) {
            $results[] = $hour . ':00';
            if ($hour < $end) {
                $results[] = $hour . ':30';
            }
        }
        return $results;
    }

}
