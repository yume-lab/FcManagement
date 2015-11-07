<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Stores Controller
 *
 * @property \App\Model\Table\StoresTable $Stores
 */
class StoresController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['StoreCategories']
        ];
        $this->set('stores', $this->paginate($this->Stores));
        $this->set('_serialize', ['stores']);
    }

    /**
     * View method
     *
     * @param string|null $id Store id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $store = $this->Stores->get($id, [
            'contain' => ['StoreCategories', 'Employees']
        ]);
        $this->set('store', $store);
        $this->set('_serialize', ['store']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $store = $this->Stores->newEntity();
        if ($this->request->is('post')) {
            $store = $this->Stores->patchEntity($store, $this->request->data);
            if ($this->Stores->save($store)) {
                $this->Flash->success(__('The store has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The store could not be saved. Please, try again.'));
            }
        }
        $storeCategories = $this->Stores->StoreCategories->find('list', ['limit' => 200]);
        $this->set(compact('store', 'storeCategories'));
        $this->set('_serialize', ['store']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Store id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $store = $this->Stores->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $store = $this->Stores->patchEntity($store, $this->request->data);
            if ($this->Stores->save($store)) {
                $this->Flash->success(__('The store has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The store could not be saved. Please, try again.'));
            }
        }
        $storeCategories = $this->Stores->StoreCategories->find('list', ['limit' => 200]);
        $this->set(compact('store', 'storeCategories'));
        $this->set('_serialize', ['store']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Store id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $store = $this->Stores->get($id);
        if ($this->Stores->delete($store)) {
            $this->Flash->success(__('The store has been deleted.'));
        } else {
            $this->Flash->error(__('The store could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }

    /**
     * 現在の店舗編集画面
     *
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function myself()
    {
        // TODO: DBもしくは設定ファイルに、シフトと絡む
        $interval = 15;
        $store = $this->Stores->get(parent::getCurrentStoreId(), [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $store = $this->Stores->patchEntity($store, $this->request->data);
            if ($this->Stores->save($store)) {
                $this->Flash->success('店舗情報を更新しました。');
                $this->UserAuth->refresh();
                return $this->redirect('/stores/myself');
            } else {
                $this->Flash->error('店舗情報の更新に失敗しました。');
            }
        }
        $this->set(compact('store', 'interval'));
        $this->set('_serialize', ['store']);
    }
}
