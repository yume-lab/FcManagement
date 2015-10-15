<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * ShiftTables Controller
 *
 * @property \App\Model\Table\ShiftTablesTable $ShiftTables
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
        $this->set('shiftTables', $this->paginate($this->ShiftTables));
        $this->set('_serialize', ['shiftTables']);
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
}
