<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;

/**
 * LatestTimeCards Controller
 * 従業員の方の勤怠打刻用ページコントローラー
 *
 * @property \App\Model\Table\LatestTimeCardsTable $LatestTimeCards
 */
class LatestTimeCardsController extends AppController
{

    /**
     * 打刻画面初期表示
     *
     * @return void
     */
    public function index()
    {
        parent::removeViewFrame();

        $this->paginate = [
            'contain' => ['Stores', 'Employees', 'TimeCardStates']
        ];
        $this->set('latestTimeCards', $this->paginate($this->LatestTimeCards));
        $this->set('_serialize', ['latestTimeCards']);

        // TODO: 現在のセッションIDを取得
        // TODO: ログアウトする
        // TODO: 取得したセッションIDをトークンとしてセッションへ
        $employees = TableRegistry::get('Employees')->findByStoreId(parent::getCurrentStoreId());
        $this->set(compact('employees'));

    }

    /**
     * TODO: 実装
     * 勤怠打刻処理を行います.
     *
     * TODO: パラメータ
     * 1. ステータス
     * 2. トークン（元管理ログイン者のセッションID）
     *
     * TODO: 打刻ステータス更新後、TimeCardsも更新
     *
     * @param string|null $id Latest Time Card id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function update($id = null)
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

}
