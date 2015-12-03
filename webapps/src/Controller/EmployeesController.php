<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;

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
            'conditions' => [
                'Employees.store_id' => parent::getCurrentStoreId(),
                'Employees.is_deleted' => false
            ],
            'limit' => Configure::read('Define.List.Count'),
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
        $options = ['associated' => ['EmployeeSalaries']];
        $employee = $this->Employees->newEntity(null, $options);
        if ($this->request->is('post')) {
            $employee = $this->Employees->patchEntity(
                $employee,
                $this->request->data,
                $options
            );
            if ($this->Employees->save($employee, $options)) {
                $this->Flash->success('登録が完了しました。');
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error('登録に失敗しました。担当者に問い合わせてください。');
            }
        }
        $roles = $this->Employees->Roles->find('list', ['limit' => 200]);
        $stores = $this->Employees->Stores->find('list', ['limit' => 200]);
        $storeId = parent::getCurrentStoreId();

        /** @var \App\Model\Table\StoreSettingsTable $StoreSettings */
        $StoreSettings = TableRegistry::get('StoreSettings');
        $setting = $StoreSettings->findByStoreId($storeId)->first();
        $defaultSalary = $setting->default_hour_pay;
        $defaultSalary = empty($defaultSalary) ? 0 : $defaultSalary;

        $this->set(compact('employee', 'roles', 'stores', 'storeId', 'defaultSalary'));
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
            'contain' => ['EmployeeSalaries'],
            'conditions' => [
                'Employees.store_id' => parent::getCurrentStoreId()
            ],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $employee = $this->Employees->patchEntity(
                $employee,
                $this->request->data,
                ['associated' => ['EmployeeSalaries']]
            );
            if ($this->Employees->save($employee)) {
                $this->Flash->success(__('従業員情報を更新しました。'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error('更新に失敗しました。担当者に問い合わせてください。');
            }
        }
        $roles = $this->Employees->Roles->find('list', ['limit' => 200]);
        $stores = $this->Employees->Stores->find('list', ['limit' => 200]);
        $this->set(compact('employee', 'roles', 'stores'));
        $this->set('_serialize', ['employee']);
    }

    /**
     * 従業員削除画面.
     * 削除時は、Employees.is_deletedを"1"(削除)に更新します
     *
     * @param string|null $id 従業員ID.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $employee = $this->Employees->get($id);
        $employee = $this->Employees->patchEntity($employee, $this->request->data);
        if ($this->Employees->save($employee)) {
            $this->Flash->success('従業員を削除しました。');
        } else {
            $this->Flash->error('削除に失敗しました。担当者に問い合わせてください。');
        }
        return $this->redirect(['action' => 'index']);
    }
}
