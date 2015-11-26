<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Network\Exception\BadRequestException;
use Cake\Controller\Component\CookieComponent;

/**
 * EmployeeTimeCards Controller
 * 勤怠関連コントローラー.
 *
 * @property \App\Model\Table\EmployeesTable $Employees
 * @property \App\Model\Table\EmployeeTimeCardsTable $EmployeeTimeCards
 * @property \App\Controller\Component\TimeCardComponent $TimeCard
 */
class EmployeeTimeCardsController extends AppController
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
    public $components = ['TimeCard', 'Cookie'];

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

        $this->UserAuth->allow(['rows', 'write']);
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
     * 初期表示.
     *
     * @return void
     */
    public function index()
    {
        /** @var \App\Model\Table\EmployeesTable $Employees */
        $Employees = TableRegistry::get('Employees');
        $employees = $Employees->findByStoreId(parent::getCurrentStoreId());
        $this->set(compact('employees'));
    }


    /**
     * 勤務表テーブルの表示を行います.
     */
    public function table()
    {
        $this->viewBuilder()->layout('');

        $employeeId = $this->request->query('employeeId');
        $month = $this->request->query('target_ym');

        $records = $this->EmployeeTimeCards->findMonthly(parent::getCurrentStoreId(), $employeeId, $month);

        $target = strtotime($month.'01');
        $showMonth = date('Y年m月', $target);
        $current = date('Y-m-d', $target);
        $next = date('Ym', strtotime(date('Y-m-1', $target). ' +1 month'));
        $prev = date('Ym', strtotime(date('Y-m-1', $target). ' -1 month'));

        /** @var \App\Model\Table\EmployeesTable $Employees */
        $Employees = TableRegistry::get('Employees');
        $employee = $Employees->get($employeeId, ['contain' => ['EmployeeSalaries']]);

        $this->log($employee);

        // 編集は1分単位で
        $times = $this->TimeCard->buildTimes($this->UserAuth->currentStore());
        $oneStepTimes = $this->TimeCard->buildTimes($this->UserAuth->currentStore(), 1);

        $this->log($records);
        $this->set(compact(
            'records', 'employee', 'showMonth', 'next', 'prev', 'current', 'times', 'oneStepTimes'
        ));
    }

    /**
     * API
     * 勤怠データ1日分の更新を行います.
     */
    public function touch()
    {
        $this->autoRender = false;

        $data = $this->request->data();
        $this->log($data);

        $ymd = $data['target'];
        $employeeId = $data['employeeId'];
        $input = $data['data'];

        $result = $this->EmployeeTimeCards->patch(parent::getCurrentStoreId(), $employeeId, $ymd, $input);
        echo json_encode(['success' => $result]);
    }

    /**
     * 勤怠入力画面を表示します.
     */
    public function input()
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
     * 勤怠入力の従業員一覧を出力します.
     */
    public function rows()
    {
        $this->assertToken($this->request->query(self::REQUEST_TOKEN_KEY));

        $states = $this->getStates();

        $records = $this->EmployeeTimeCards->findAllEmployees($this->storeId);
        $this->set(compact('records', 'states'));
    }

    /**
     * API
     * 勤怠データの書き込みを行います.
     */
    public function write()
    {
        $this->autoRender = false;

        $data = $this->request->data();

        $this->log($data);
        $this->assertToken($data[self::REQUEST_TOKEN_KEY]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $employeeId = $data['employeeId'];
            $path = $data['path'];
            $time = $data['time'];
            $additions = [];
            $additions['break_minute'] = $data['break_minute'];

            $result = $this->EmployeeTimeCards->write($this->storeId, $employeeId, $path, $time, $additions);

            $this->log($result);
            echo json_encode(['success' => $result]);
        }
    }

    /**
     * 勤怠状態を取得します.
     *
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
