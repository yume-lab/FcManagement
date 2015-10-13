<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * UserStores Controller
 *
 * @property \App\Model\Table\UserStoresTable $UserStores
 */
class UserStoresController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Users', 'Stores', 'Roles']
        ];
        $this->set('userStores', $this->paginate($this->UserStores));
        $this->set('_serialize', ['userStores']);
    }

    /**
     * View method
     *
     * @param string|null $id User Store id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $userStore = $this->UserStores->get($id, [
            'contain' => ['Users', 'Stores', 'Roles']
        ]);
        $this->set('userStore', $userStore);
        $this->set('_serialize', ['userStore']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $userStore = $this->UserStores->newEntity();
        if ($this->request->is('post')) {
            $userStore = $this->UserStores->patchEntity($userStore, $this->request->data);
            if ($this->UserStores->save($userStore)) {
                $this->Flash->success(__('The user store has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The user store could not be saved. Please, try again.'));
            }
        }
        $users = $this->UserStores->Users->find('list', ['limit' => 200]);
        $stores = $this->UserStores->Stores->find('list', ['limit' => 200]);
        $roles = $this->UserStores->Roles->find('list', ['limit' => 200]);
        $this->set(compact('userStore', 'users', 'stores', 'roles'));
        $this->set('_serialize', ['userStore']);
    }

    /**
     * Edit method
     *
     * @param string|null $id User Store id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $userStore = $this->UserStores->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $userStore = $this->UserStores->patchEntity($userStore, $this->request->data);
            if ($this->UserStores->save($userStore)) {
                $this->Flash->success(__('The user store has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The user store could not be saved. Please, try again.'));
            }
        }
        $users = $this->UserStores->Users->find('list', ['limit' => 200]);
        $stores = $this->UserStores->Stores->find('list', ['limit' => 200]);
        $roles = $this->UserStores->Roles->find('list', ['limit' => 200]);
        $this->set(compact('userStore', 'users', 'stores', 'roles'));
        $this->set('_serialize', ['userStore']);
    }

    /**
     * Delete method
     *
     * @param string|null $id User Store id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $userStore = $this->UserStores->get($id);
        if ($this->UserStores->delete($userStore)) {
            $this->Flash->success(__('The user store has been deleted.'));
        } else {
            $this->Flash->error(__('The user store could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
