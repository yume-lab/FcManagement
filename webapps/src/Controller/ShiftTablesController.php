<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;

/**
 * ShiftTables Controller
 *
 * @property \App\Model\Table\ShiftTablesTable $ShiftTables
 * @property \App\Model\Table\EmployeesTable $Employees
 */
class ShiftTablesController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $Employees = TableRegistry::get('Employees');
        $this->set('employees', $Employees->find()->where(['Employees.is_deleted' => false]));
        $this->set('_serialize', ['employees']);
    }

    /**
     * View method
     *
     * @param string|null $id Shift Table id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $shiftTable = $this->ShiftTables->get($id, [
            'contain' => []
        ]);
        $this->set('shiftTable', $shiftTable);
        $this->set('_serialize', ['shiftTable']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $shiftTable = $this->ShiftTables->newEntity();
        if ($this->request->is('post')) {
            $shiftTable = $this->ShiftTables->patchEntity($shiftTable, $this->request->data);
            if ($this->ShiftTables->save($shiftTable)) {
                $this->Flash->success(__('The shift table has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The shift table could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('shiftTable'));
        $this->set('_serialize', ['shiftTable']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Shift Table id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $shiftTable = $this->ShiftTables->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $shiftTable = $this->ShiftTables->patchEntity($shiftTable, $this->request->data);
            if ($this->ShiftTables->save($shiftTable)) {
                $this->Flash->success(__('The shift table has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The shift table could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('shiftTable'));
        $this->set('_serialize', ['shiftTable']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Shift Table id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $shiftTable = $this->ShiftTables->get($id);
        if ($this->ShiftTables->delete($shiftTable)) {
            $this->Flash->success(__('The shift table has been deleted.'));
        } else {
            $this->Flash->error(__('The shift table could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }

    /**
     * シフト初期表示時のAPI.
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
     * シフトの一時保存を行います.
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
     * @param $data パラメータのシフトデータ
     * @return array 整形した配列
     */
    private function buildBody($data)
    {
        $useKeys = ['id', 'title', 'employeeId', 'start', 'end'];

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

}
