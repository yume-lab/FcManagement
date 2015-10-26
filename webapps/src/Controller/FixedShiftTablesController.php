<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * FixedShiftTables Controller
 *
 * @property \App\Model\Table\FixedShiftTablesTable $FixedShiftTables
 */
class FixedShiftTablesController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Stores']
        ];
        $this->set('fixedShiftTables', $this->paginate($this->FixedShiftTables));
        $this->set('_serialize', ['fixedShiftTables']);
    }

    /**
     * View method
     *
     * @param string|null $id Fixed Shift Table id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $fixedShiftTable = $this->FixedShiftTables->get($id, [
            'contain' => ['Stores']
        ]);
        $this->set('fixedShiftTable', $fixedShiftTable);
        $this->set('_serialize', ['fixedShiftTable']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $fixedShiftTable = $this->FixedShiftTables->newEntity();
        if ($this->request->is('post')) {
            $fixedShiftTable = $this->FixedShiftTables->patchEntity($fixedShiftTable, $this->request->data);
            if ($this->FixedShiftTables->save($fixedShiftTable)) {
                $this->Flash->success(__('The fixed shift table has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The fixed shift table could not be saved. Please, try again.'));
            }
        }
        $stores = $this->FixedShiftTables->Stores->find('list', ['limit' => 200]);
        $this->set(compact('fixedShiftTable', 'stores'));
        $this->set('_serialize', ['fixedShiftTable']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Fixed Shift Table id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $fixedShiftTable = $this->FixedShiftTables->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $fixedShiftTable = $this->FixedShiftTables->patchEntity($fixedShiftTable, $this->request->data);
            if ($this->FixedShiftTables->save($fixedShiftTable)) {
                $this->Flash->success(__('The fixed shift table has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The fixed shift table could not be saved. Please, try again.'));
            }
        }
        $stores = $this->FixedShiftTables->Stores->find('list', ['limit' => 200]);
        $this->set(compact('fixedShiftTable', 'stores'));
        $this->set('_serialize', ['fixedShiftTable']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Fixed Shift Table id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $fixedShiftTable = $this->FixedShiftTables->get($id);
        if ($this->FixedShiftTables->delete($fixedShiftTable)) {
            $this->Flash->success(__('The fixed shift table has been deleted.'));
        } else {
            $this->Flash->error(__('The fixed shift table could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
