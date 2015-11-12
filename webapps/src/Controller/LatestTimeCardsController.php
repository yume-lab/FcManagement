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
     * 初期処理.
     * @return void
     */
    public function initialize() {
        parent::initialize();

        $this->UserAuth->allow(['write']);
    }


    /**
     * 打刻画面初期表示
     *
     * @return void
     */
    public function index()
    {
        parent::removeViewFrame();
        $storeId = parent::getCurrentStoreId();

        $this->paginate = [
            'contain' => ['Employees', 'TimeCardStates'],
            'conditions' => [
                'LatestTimeCards.store_id' => $storeId
            ],
        ];

        // TODO: 現在のセッションIDを取得
        // TODO: ログアウトする
        // TODO: 取得したセッションIDをトークンとしてセッションへ
        $employees = TableRegistry::get('Employees')->findByStoreId($storeId);
        $timeCardStates = TableRegistry::get('TimeCardStates')->find('all');
        $latestTimeCards = $this->LatestTimeCards->find()->where(['store_id' => $storeId]);

        $data = [];
        foreach ($latestTimeCards as $latest) {
            $data[$latest->employee_id] = $latest;
        }

        $states = [];
        foreach ($timeCardStates as $state) {
            $states[$state->id] = $state;
        }

        $this->set(compact('employees', 'storeId', 'data', 'states'));
    }

    /**
     * API
     * 勤怠打刻処理を行います.
     *
     * TODO: パラメータ
     * 1. ステータス
     * 2. トークン（元管理ログイン者のセッションID）
     *
     * TODO: 打刻ステータス更新後、TimeCardsも更新
     *
     * @return void Redirects on successful edit, renders view otherwise.
     */
    public function write()
    {
        $this->autoRender = false;

        $data = $this->request->data();
        $this->log($data);

        // TODO: トークンチェック

        if ($this->request->is(['patch', 'post', 'put'])) {
            $employeeId = $data['employeeId'];
            $storeId = $data['storeId'];
            $alias = $data['alias'];

            $state = TableRegistry::get('TimeCardStates')->findByAlias($alias)->first();

            $entity = $this->LatestTimeCards->find()
                ->where(['store_id' => $storeId])
                ->where(['employee_id' => $employeeId])
                ->first();
            if (empty($entity)) {
                $entity = $this->LatestTimeCards->newEntity();
            }
            $record = [
                'store_id' => $storeId,
                'employee_id' => $employeeId,
                'time_card_state_id' => $state->id
            ];

            $latestTimeCards = $this->LatestTimeCards->patchEntity($entity, $record);
            $isSuccess = ($this->LatestTimeCards->save($latestTimeCards));

            // TODO: time_cardsにも累積で登録
            echo json_encode(['success' => $isSuccess]);
        }
    }

}
