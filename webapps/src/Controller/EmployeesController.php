<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Employees Controller
 * パート管理用コントローラ
 *
 * @property \App\Model\Table\EmployeesTable $Employees
 */
class EmployeesController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Roles', 'Stores'],
            'conditions' => ['store_id' => parent::getCurrentStoreId()],
            'limit' => 10,
            'order' => [
                'Employees.id' => 'asc'
            ]
        ];
        $this->set('employees', $this->paginate($this->Employees));
        $this->set('_serialize', ['employees']);
    }

    /**
     * 従業員追加画面.
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $employee = $this->Employees->newEntity();
        if ($this->request->is('post')) {
            $employee = $this->Employees->patchEntity($employee, $this->request->data);
            if ($this->Employees->save($employee)) {
                $this->Flash->success(__('登録が完了しました'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The employee could not be saved. Please, try again.'));
            }
        }
        $roles = $this->Employees->Roles->find('list', ['limit' => 200]);
        $stores = $this->Employees->Stores->find('list', ['limit' => 200]);
        $storeId = parent::getCurrentStoreId();
        $this->set(compact('employee', 'roles', 'stores', 'storeId'));
        $this->set('_serialize', ['employee']);
    }

    /**
     * 従業員更新画面.
     *
     * @param string|null $id 従業員ID.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $employee = $this->Employees->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $employee = $this->Employees->patchEntity($employee, $this->request->data);
            if ($this->Employees->save($employee)) {
                $this->Flash->success(__('従業員情報を更新しました。'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The employee could not be saved. Please, try again.'));
            }
        }
        $roles = $this->Employees->Roles->find('list', ['limit' => 200]);
        $stores = $this->Employees->Stores->find('list', ['limit' => 200]);
        $this->set(compact('employee', 'roles', 'stores'));
        $this->set('_serialize', ['employee']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Employee id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $employee = $this->Employees->get($id);
        if ($this->Employees->delete($employee)) {
            $this->Flash->success(__('The employee has been deleted.'));
        } else {
            $this->Flash->error(__('The employee could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
