<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * LatestTimeCards Controller
 *
 * @property \App\Model\Table\LatestTimeCardsTable $LatestTimeCards
 */
class LatestTimeCardsController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Stores', 'Employees', 'TimeCardStates']
        ];
        $this->set('latestTimeCards', $this->paginate($this->LatestTimeCards));
        $this->set('_serialize', ['latestTimeCards']);
    }

    /**
     * View method
     *
     * @param string|null $id Latest Time Card id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $latestTimeCard = $this->LatestTimeCards->get($id, [
            'contain' => ['Stores', 'Employees', 'TimeCardStates']
        ]);
        $this->set('latestTimeCard', $latestTimeCard);
        $this->set('_serialize', ['latestTimeCard']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $latestTimeCard = $this->LatestTimeCards->newEntity();
        if ($this->request->is('post')) {
            $latestTimeCard = $this->LatestTimeCards->patchEntity($latestTimeCard, $this->request->data);
            if ($this->LatestTimeCards->save($latestTimeCard)) {
                $this->Flash->success(__('The latest time card has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The latest time card could not be saved. Please, try again.'));
            }
        }
        $stores = $this->LatestTimeCards->Stores->find('list', ['limit' => 200]);
        $employees = $this->LatestTimeCards->Employees->find('list', ['limit' => 200]);
        $timeCardStates = $this->LatestTimeCards->TimeCardStates->find('list', ['limit' => 200]);
        $this->set(compact('latestTimeCard', 'stores', 'employees', 'timeCardStates'));
        $this->set('_serialize', ['latestTimeCard']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Latest Time Card id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $latestTimeCard = $this->LatestTimeCards->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $latestTimeCard = $this->LatestTimeCards->patchEntity($latestTimeCard, $this->request->data);
            if ($this->LatestTimeCards->save($latestTimeCard)) {
                $this->Flash->success(__('The latest time card has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The latest time card could not be saved. Please, try again.'));
            }
        }
        $stores = $this->LatestTimeCards->Stores->find('list', ['limit' => 200]);
        $employees = $this->LatestTimeCards->Employees->find('list', ['limit' => 200]);
        $timeCardStates = $this->LatestTimeCards->TimeCardStates->find('list', ['limit' => 200]);
        $this->set(compact('latestTimeCard', 'stores', 'employees', 'timeCardStates'));
        $this->set('_serialize', ['latestTimeCard']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Latest Time Card id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $latestTimeCard = $this->LatestTimeCards->get($id);
        if ($this->LatestTimeCards->delete($latestTimeCard)) {
            $this->Flash->success(__('The latest time card has been deleted.'));
        } else {
            $this->Flash->error(__('The latest time card could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
