<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;

/**
 * LatestTimeCards Controller
 * 従業員の方の勤怠打刻用ページコントローラー
 *
 * @property \App\Model\Table\LatestTimeCardsTable $LatestTimeCards
 * @property \App\Model\Table\TimeCardStatesTable $TimeCardStates
 * @property \App\Model\Table\TimeCardsTable $TimeCards
 * @property \App\Model\Table\EmployeesTable $Employees
 */
class LatestTimeCardsController extends AppController
{

    public $helpers = ['TimeCard'];

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

        // TODO: 現在のセッションIDを取得
        // TODO: ログアウトする
        // TODO: 取得したセッションIDをトークンとしてセッションへ

        $states = $this->getStates();
        $this->set(compact('storeId', 'states'));

    }

    /**
     * 従業員一覧部分を表示します.
     * このアクションはJSから呼ばれ、レンダリング済のHTMLをJSで組み込んでいる形です.
     */
    public function table()
    {
        $storeId = $this->request->query('storeId');
        $states = $this->getStates();

        $Employees = TableRegistry::get('Employees');
        $employees = $Employees->findByStoreId($storeId);

        $latestTimeCards = $this->LatestTimeCards->find()->where(['store_id' => $storeId]);
        $data = [];
        foreach ($latestTimeCards as $latest) {
            $data[$latest->employee_id] = $latest;
        }

        $this->set(compact('employees', 'data', 'states'));
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
            $time = $data['time'];

            $state = TableRegistry::get('TimeCardStates')->findByAlias($alias)->first();

            $TimeCards = TableRegistry::get('TimeCards');

            $isSuccess = $this->LatestTimeCards->write($employeeId, $storeId, $state->id, $time)
                && $TimeCards->write($employeeId, $storeId, $state->id, $time);

            echo json_encode(['success' => $isSuccess]);
        }
    }

    /**
     * 勤怠状態一覧を取得します.
     * @return array
     */
    private function getStates()
    {
        $TimeCardStates = TableRegistry::get('TimeCardStates');
        $timeCardStates = $TimeCardStates->find('all');
        $states = [];
        foreach ($timeCardStates as $state) {
            $states[$state->id] = $state;
        }
        return $states;
    }

}
