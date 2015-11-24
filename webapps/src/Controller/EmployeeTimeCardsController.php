<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;

/**
 * EmployeeTimeCards Controller
 * 勤怠関連コントローラー.
 *
 * @property \App\Model\Table\EmployeesTable $Employees
 * @property \App\Model\Table\EmployeeTimeCardsTable $EmployeeTimeCards
 * @property \App\Controller\Component\TimeCardComponent $TimeCard
 */
class EmployeeTimeCardsController extends AppController
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
        /** @var \App\Model\Table\EmployeesTable $Employees */
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
        $month = $this->request->query('target_ym');

        $records = $this->EmployeeTimeCards->findMonthly(parent::getCurrentStoreId(), $employeeId, $month);

        $target = strtotime($month.'01');
        $showMonth = date('Y年m月', $target);
        $current = date('Y-m-d', $target);
        $next = date('Ym', strtotime(date('Y-m-1', $target). ' +1 month'));
        $prev = date('Ym', strtotime(date('Y-m-1', $target). ' -1 month'));

        // 編集は1分単位で
        $times = $this->TimeCard->buildTimes($this->UserAuth->currentStore(), 1);

        $this->log($records);
        $this->set(compact('records', 'employee', 'showMonth', 'next', 'prev', 'current', 'times'));
    }

    public function input()
    {

    }

    public function rows()
    {

    }

    public function write()
    {

    }



    

    /**
     * View method
     *
     * @param string|null $id Employee Time Card id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $employeeTimeCard = $this->EmployeeTimeCards->get($id, [
            'contain' => ['Stores', 'Employees']
        ]);
        $this->set('employeeTimeCard', $employeeTimeCard);
        $this->set('_serialize', ['employeeTimeCard']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $employeeTimeCard = $this->EmployeeTimeCards->newEntity();
        if ($this->request->is('post')) {
            $employeeTimeCard = $this->EmployeeTimeCards->patchEntity($employeeTimeCard, $this->request->data);
            if ($this->EmployeeTimeCards->save($employeeTimeCard)) {
                $this->Flash->success(__('The employee time card has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The employee time card could not be saved. Please, try again.'));
            }
        }
        $stores = $this->EmployeeTimeCards->Stores->find('list', ['limit' => 200]);
        $employees = $this->EmployeeTimeCards->Employees->find('list', ['limit' => 200]);
        $this->set(compact('employeeTimeCard', 'stores', 'employees'));
        $this->set('_serialize', ['employeeTimeCard']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Employee Time Card id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $employeeTimeCard = $this->EmployeeTimeCards->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $employeeTimeCard = $this->EmployeeTimeCards->patchEntity($employeeTimeCard, $this->request->data);
            if ($this->EmployeeTimeCards->save($employeeTimeCard)) {
                $this->Flash->success(__('The employee time card has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The employee time card could not be saved. Please, try again.'));
            }
        }
        $stores = $this->EmployeeTimeCards->Stores->find('list', ['limit' => 200]);
        $employees = $this->EmployeeTimeCards->Employees->find('list', ['limit' => 200]);
        $this->set(compact('employeeTimeCard', 'stores', 'employees'));
        $this->set('_serialize', ['employeeTimeCard']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Employee Time Card id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $employeeTimeCard = $this->EmployeeTimeCards->get($id);
        if ($this->EmployeeTimeCards->delete($employeeTimeCard)) {
            $this->Flash->success(__('The employee time card has been deleted.'));
        } else {
            $this->Flash->error(__('The employee time card could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
