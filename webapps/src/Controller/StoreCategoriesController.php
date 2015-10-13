<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * StoreCategories Controller
 *
 * @property \App\Model\Table\StoreCategoriesTable $StoreCategories
 */
class StoreCategoriesController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->set('storeCategories', $this->paginate($this->StoreCategories));
        $this->set('_serialize', ['storeCategories']);
    }

    /**
     * View method
     *
     * @param string|null $id Store Category id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $storeCategory = $this->StoreCategories->get($id, [
            'contain' => ['Stores']
        ]);
        $this->set('storeCategory', $storeCategory);
        $this->set('_serialize', ['storeCategory']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $storeCategory = $this->StoreCategories->newEntity();
        if ($this->request->is('post')) {
            $storeCategory = $this->StoreCategories->patchEntity($storeCategory, $this->request->data);
            if ($this->StoreCategories->save($storeCategory)) {
                $this->Flash->success(__('The store category has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The store category could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('storeCategory'));
        $this->set('_serialize', ['storeCategory']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Store Category id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $storeCategory = $this->StoreCategories->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $storeCategory = $this->StoreCategories->patchEntity($storeCategory, $this->request->data);
            if ($this->StoreCategories->save($storeCategory)) {
                $this->Flash->success(__('The store category has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The store category could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('storeCategory'));
        $this->set('_serialize', ['storeCategory']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Store Category id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $storeCategory = $this->StoreCategories->get($id);
        if ($this->StoreCategories->delete($storeCategory)) {
            $this->Flash->success(__('The store category has been deleted.'));
        } else {
            $this->Flash->error(__('The store category could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
