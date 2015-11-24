<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * EmployeeTimeCards Controller
 *
 * @property \App\Model\Table\EmployeeTimeCardsTable $EmployeeTimeCards
 */
class EmployeeTimeCardsController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Stores', 'Employees']
        ];
        $this->set('employeeTimeCards', $this->paginate($this->EmployeeTimeCards));
        $this->set('_serialize', ['employeeTimeCards']);
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
