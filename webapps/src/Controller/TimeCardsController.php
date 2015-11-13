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
 */
class TimeCardsController extends AppController
{

    /**
     * 使用ヘルパー
     * @var array
     */
    public $helpers = ['TimeCard'];

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

        $this->log($matrix);
        $this->set(compact('matrix', 'employee'));
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
            $matrix[$day] = $tmp;
        }
        return $matrix;
    }

    /**
     * View method
     *
     * @param string|null $id Time Card id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $timeCard = $this->TimeCards->get($id, [
            'contain' => ['Stores', 'Employees']
        ]);
        $this->set('timeCard', $timeCard);
        $this->set('_serialize', ['timeCard']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $timeCard = $this->TimeCards->newEntity();
        if ($this->request->is('post')) {
            $timeCard = $this->TimeCards->patchEntity($timeCard, $this->request->data);
            if ($this->TimeCards->save($timeCard)) {
                $this->Flash->success(__('The time card has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The time card could not be saved. Please, try again.'));
            }
        }
        $stores = $this->TimeCards->Stores->find('list', ['limit' => 200]);
        $employees = $this->TimeCards->Employees->find('list', ['limit' => 200]);
        $this->set(compact('timeCard', 'stores', 'employees'));
        $this->set('_serialize', ['timeCard']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Time Card id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $timeCard = $this->TimeCards->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $timeCard = $this->TimeCards->patchEntity($timeCard, $this->request->data);
            if ($this->TimeCards->save($timeCard)) {
                $this->Flash->success(__('The time card has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The time card could not be saved. Please, try again.'));
            }
        }
        $stores = $this->TimeCards->Stores->find('list', ['limit' => 200]);
        $employees = $this->TimeCards->Employees->find('list', ['limit' => 200]);
        $this->set(compact('timeCard', 'stores', 'employees'));
        $this->set('_serialize', ['timeCard']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Time Card id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $timeCard = $this->TimeCards->get($id);
        if ($this->TimeCards->delete($timeCard)) {
            $this->Flash->success(__('The time card has been deleted.'));
        } else {
            $this->Flash->error(__('The time card could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }

}
