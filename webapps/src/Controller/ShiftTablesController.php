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
    public function get() {
        $this->autoRender = false;

        $query = $this->request->query;

        $start = $this->getDateConditions($query['start']);
        $end = $this->getDateConditions($query['end']);

        $shifts = $this->ShiftTables->find()
            ->where(['store_id' => parent::getCurrentStoreId()])
            ->where(['year >= ' => $start[0], 'month >= ' => $start[1]])
            ->where(['year <= ' => $end[0], 'month <= ' => $end[1]])
            ->all();

        $response = [];
        foreach ($shifts as $shift) {
            $data = json_decode($shift->body);
            $response = array_merge($response, $data);
        }
        $this->log($response);

        echo json_encode($response);
    }

    public function update() {
        $this->autoRender = false;

        $data = $this->request->data();
        $useKeys = ['id', 'title', 'backgroundColor', 'start', 'end', 'allDay'];

        if ($this->request->is(['patch', 'post', 'put'])) {
            $year = $data['year'];
            $month = $data['month'];
            $shift = $data['shift'];
            $shiftTable = $this->ShiftTables->find()->where([
                    'store_id' => parent::getCurrentStoreId(),
                    'year' => $year,
                    'month' => $month]
            )->first();
            if (empty($shiftTable)) {
                $this->log('if (empty($shiftTable)) {');
                $shiftTable = $this->ShiftTables->newEntity();
            }
            $record = [
                'store_id' => parent::getCurrentStoreId(),
                'year' => $year,
                'month' => $month,
                'body' => json_encode($shift),
                'is_deleted' => false
            ];

            $shiftTable = $this->ShiftTables->patchEntity($shiftTable, $record);
            if ($this->ShiftTables->save($shiftTable)) {
                $this->Flash->success('シフト表を更新しました。');
            } else {
                $this->Flash->error('シフト表の更新に失敗しました。');
            }
            return $this->redirect(['action' => 'index']);
        }
    }

    /**
     * シフトを取得する条件を取得します.
     *
     * @param $date パラメータの日付
     * @return array [0] => year, [1] => month の配列
     */
    private function getDateConditions($date) {
        return explode('-', date('Y-m', strtotime($date)));
    }

}
