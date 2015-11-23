<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Network\Exception\BadRequestException;
use Cake\ORM\TableRegistry;
use Cake\Controller\Component\CookieComponent;

/**
 * LatestTimeCards Controller
 * 従業員の方の勤怠打刻用ページコントローラー
 *
 * @property \App\Model\Table\LatestTimeCardsTable $LatestTimeCards
 * @property \App\Model\Table\TimeCardStatesTable $TimeCardStates
 * @property \App\Model\Table\TimeCardsTable $TimeCards
 * @property \App\Model\Table\EmployeesTable $Employees
 * @property \Cake\Controller\Component\CookieComponent $Cookie
 */
class LatestTimeCardsController extends AppController
{

    /**
     * 使用ヘルパー
     * @var array
     */
    public $helpers = ['TimeCard'];

    /**
     * 使用コンポーネント
     * @var array
     */
    public $components = ['Cookie'];

    /**
     * 店舗ID
     * @var int
     */
    private $storeId;

    /**
     * 認証用トークン
     * @var int
     */
    private $token;

    /**
     * クッキー: 認証トークン
     */
    const TOKEN_KEY = 'TimeCard.user.token';

    /**
     * リクエストで飛んでくるトークンのキー
     */
    const REQUEST_TOKEN_KEY = 'token';

    /**
     * クッキー: 店舗ID
     */
    const STORE_ID_KEY = 'TimeCard.user.storeId';

    /**
     * 初期処理.
     * @return void
     */
    public function initialize() {
        parent::initialize();

        $this->UserAuth->allow(['table', 'write']);
    }

    /**
     * リクエスト毎の処理.
     *
     * @param Event $event
     * @return void
     */
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        // 保持は1ヶ月
        $this->Cookie->configKey('TimeCard', 'expires', '+1 months');

        $this->storeId = $this->Cookie->read(self::STORE_ID_KEY);
        $this->token = $this->Cookie->read(self::TOKEN_KEY);
    }

    /**
     * 打刻画面初期表示
     *
     * @return void
     */
    public function index()
    {
        parent::removeViewFrame();

        $token = sha1(ceil(microtime(true)*1000));
        $this->Cookie->write(self::TOKEN_KEY, $token);
        $this->Cookie->write(self::STORE_ID_KEY, parent::getCurrentStoreId());

        $this->Flash->error('再度ログインしてください。');
        $this->UserAuth->logout();

        $states = $this->getStates();
        $this->set(compact('states', 'token'));
    }

    /**
     * 従業員一覧部分を表示します.
     * このアクションはJSから呼ばれ、レンダリング済のHTMLをJSで組み込んでいる形です.
     */
    public function table()
    {
        $this->assertToken($this->request->query(self::REQUEST_TOKEN_KEY));

        $states = $this->getStates();

        /** @var \App\Model\Table\EmployeesTable $Employees */
        $Employees = TableRegistry::get('Employees');
        $employees = $Employees->findByStoreId($this->storeId);

        $latestTimeCards = $this->LatestTimeCards->find()->where(['store_id' => $this->storeId]);
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
     * @return void Redirects on successful edit, renders view otherwise.
     */
    public function write()
    {
        $this->autoRender = false;

        $data = $this->request->data();

        $this->log($data);
        $this->assertToken($data[self::REQUEST_TOKEN_KEY]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $employeeId = $data['employeeId'];
            $alias = $data['alias'];
            $time = $data['time'];

            /** @var \App\Model\Table\TimeCardStatesTable $TimeCardStates */
            $TimeCardStates = TableRegistry::get('TimeCardStates');
            $state = $TimeCardStates->findByAlias($alias)->first();

            /** @var \App\Model\Table\TimeCardsTable $TimeCards */
            $TimeCards = TableRegistry::get('TimeCards');

            $isSuccess = $this->LatestTimeCards->write($employeeId, $this->storeId, $state->id, $time)
                && $TimeCards->write($employeeId, $this->storeId, $state, $time);

            echo json_encode(['success' => $isSuccess]);
        }
    }

    /**
     * 勤怠状態一覧を取得します.
     * @return array
     */
    private function getStates()
    {
        /** @var \App\Model\Table\TimeCardStatesTable $TimeCardStates */
        $TimeCardStates = TableRegistry::get('TimeCardStates');
        $timeCardStates = $TimeCardStates->find('all');
        $states = [];
        foreach ($timeCardStates as $state) {
            $states[$state->id] = $state;
        }
        return $states;
    }

    /**
     * トークンチェックを行います.
     * @param $token string リクエストされたトークン
     * @return bool 発行時のトークンと同じであればOK
     * @throws BadRequestException トークンが合わなかった場合
     */
    private function assertToken($token)
    {
        $this->log($token);
        $this->log($this->token);
        if ($this->token == $token) {
            return true;
        }
        throw new BadRequestException();
    }

}