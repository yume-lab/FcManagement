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
 */
class ShiftTablesController extends AppController
{

    /**
     * シフト編集画面
     *
     * @return void
     */
    public function edit()
    {
        $Employees = TableRegistry::get('Employees');

        $store = $this->UserAuth->currentStore();
        $opened = date('H:i:s', strtotime($store->opened));
        $closed = date('H:i:s', strtotime($store->closed));

        $this->set('opened', $opened);
        $this->set('closed', $closed);
        $this->set('times', $this->buildSelectableTime($opened, $closed));
        $this->set('employees', $Employees->find()->where(['Employees.is_deleted' => false, 'Employees.store_id' => parent::getCurrentStoreId()]));
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
            $FixedShiftTables = TableRegistry::get('FixedShiftTables');
            $shiftTable = $FixedShiftTables->newEntity();

            $targetYm = $data['fixed_year'].$data['fixed_month'];
            $shift = json_decode($data['fixed_shift']);
            $hash = sha1(ceil(microtime(true)*1000));
            $record = [
                'store_id' => parent::getCurrentStoreId(),
                'target_ym' => $targetYm,
                'hash' => $hash,
                'body' => json_encode($this->buildBody($shift)),
                'is_deleted' => false
            ];

            $this->log($record);
            $FixedShiftTables->removeAllByTargetYm($targetYm);
            $shiftTable = $FixedShiftTables->patchEntity($shiftTable, $record);
            if ($FixedShiftTables->save($shiftTable)) {
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
