<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;

/**
 * ShiftTables Controller
 * シフト作成コントローラ
 *
 * @property \App\Model\Table\ShiftTablesTable $ShiftTables
 * @property \App\Model\Table\FixedShiftTablesTable.php $FixedShiftTables
 * @property \App\Model\Table\EmployeesTable $Employees
 * @property \App\Controller\Component\TimeCardComponent $TimeCard
 */
class ShiftTablesController extends AppController
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
     * シフト編集画面
     *
     * @return void
     */
    public function edit()
    {
        $store = $this->UserAuth->currentStore();
        // TODO: DBもしくは設定ファイルに、シフトと絡む
        $interval = 15;
        $times = $this->TimeCard->buildTimes($store);

        /** @var \App\Model\Table\EmployeesTable $Employees */
        $Employees = TableRegistry::get('Employees');
        $employees = $Employees->findByStoreId($store->id);

        $opened = date('H:i:s', strtotime($store->opened));
        $closed = date('H:i:s', strtotime($store->closed));

        /** @var \App\Model\Table\StoreSettingsTable $StoreSettings */
        $StoreSettings = TableRegistry::get('StoreSettings');
        $setting = $StoreSettings->findByStoreId($store->id)->first();
        $break = $setting->rested_times;

        $this->set(compact('opened', 'closed', 'interval', 'times', 'employees', 'break'));
        $this->set('_serialize', ['employees']);
    }

    /**
     * シフト確定アクション.
     * シフト確定テーブルにデータを登録する.
     */
    public function fixed()
    {
        $data = $this->request->data();
        $this->log($data);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $targetYm = $data['fixed_year'].$data['fixed_month'];
            $shift = json_decode($data['fixed_shift']);
            $storeId = parent::getCurrentStoreId();

            /** @var \App\Model\Table\FixedShiftTablesTable $FixedShiftTables */
            $FixedShiftTables = TableRegistry::get('FixedShiftTables');
            $FixedShiftTables->removeAllByTargetYm($targetYm);

            $body = $this->buildBody($shift);
            $result = $this->ShiftTables->patch($storeId, $targetYm, $body)
                && $FixedShiftTables->add($storeId, $targetYm, $body);
            if ($result) {
                $this->Flash->success('シフト表が作成されました。');
            } else {
                $this->Flash->error('シフト表作成に失敗しました。');
            }
        }
        return $this->redirect(['action' => 'edit']);
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
     * TODO: 実装とフロントの修正
     * API
     * シフト表に表示する従業員を取得します.
     */
    public function getResources()
    {

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
            $body = $this->buildBody($shift);
            $result = $this->ShiftTables->patch(parent::getCurrentStoreId(), $targetYm, $body);
            echo json_encode(['success' => $result]);
        }
    }

    /**
     * シフトデータを、保存用に整形します.
     * @param $data array パラメータのシフトデータ
     * @return array 整形した配列
     */
    private function buildBody($data)
    {
        //$useKeys = ['_id', 'title', 'employeeId', 'start', 'end'];
        $useKeys = ['_id', 'employeeId', 'start', 'end'];

        $results = [];
        foreach ($data as $shift) {
            $shift = (is_object($shift)) ? (array)$shift : $shift;
            $tmp = [];
            foreach ($useKeys as $key) {
                $tmp[$key] = $shift[$key];
            }
            $results[] = $tmp;
        }
        return $results;
    }

}
