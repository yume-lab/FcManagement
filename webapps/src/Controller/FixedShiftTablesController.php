<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cake\Network\Exception\NotFoundException;

/**
 * FixedShiftTables Controller
 * 確定シフト閲覧用コントローラ.
 *
 * @property \App\Model\Table\FixedShiftTablesTable $FixedShiftTables
 * @property \App\Model\Table\EmployeesTable $Employees
 */
class FixedShiftTablesController extends AppController
{

    /**
     * 初期処理.
     */
    public function initialize() {
        parent::initialize();

        $this->UserAuth->allow(['output']);
    }

    /**
     * 確定シフトの一覧表示
     *
     * @return void
     */
    public function index()
    {
        $this->paginate = [
            'conditions' => [
                'FixedShiftTables.store_id' => parent::getCurrentStoreId(),
                'FixedShiftTables.is_deleted' => false
            ],
            'limit' => Configure::read('Define.List.Count'),
            'order' => [
                'FixedShiftTables.id' => 'asc'
            ]
        ];
        $this->set('fixedShiftTables', $this->paginate($this->FixedShiftTables));
        $this->set('_serialize', ['fixedShiftTables']);
    }

    /**
     * シフト表表示.
     * このアクションは未ログインでも見れる.
     *
     * TODO: ログインしなくても見れるように
     * TODO: レイアウト整える
     *
     * @param string|null $hash シフト表のハッシュ
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     * @return void
     */
    public function view($hash = null)
    {
        parent::removeViewFrame();

        $data = $this->FixedShiftTables->find()
            ->where(['hash' => $hash])
            ->where(['is_deleted' => false])
            ->first();

        if (empty($data)) {
            throw new NotFoundException();
        }
        /** @var \App\Model\Table\EmployeesTable $Employees */
        $Employees = TableRegistry::get('Employees');
        $employees = $Employees->findByStoreId(parent::getCurrentStoreId());
        $this->set(compact('data', 'employees'));
        $this->set('_serialize', ['data', 'employees']);
    }

    /**
     * 確定されたシフトのPDF出力用Actionです.
     * これはバッチからのみアクセスされます.
     * @param null $hash
     */
    public function output($hash = null)
    {
        $this->viewBuilder()->layout('Pdf/shift');

        $data = $this->FixedShiftTables->find()
            ->where(['hash' => $hash])
            ->where(['is_deleted' => false])
            ->first();

        if (empty($data)) {
            throw new NotFoundException();
        }
        /** @var \App\Model\Table\EmployeesTable $Employees */
        $Employees = TableRegistry::get('Employees');
        $employees = $Employees->findByStoreId($data->store_id);
        $this->set(compact('data', 'employees'));
        $this->set('_serialize', ['data', 'employees']);

        $this->render('Pdf/output');
    }

    public function printing($hash = null)
    {
        $this->autoRender = false;

        // TODO: 全体的にシェルにする

        $bin = ROOT . DS . 'bin' . DS;
        $script = $bin . 'OutHtml.js';
        $outPath = TMP . 'pdf/shift/';

        $outHtml = $outPath.$hash.'.html';
        $outPdf = $outPath.$hash.'.pdf';

        $command = '/usr/local/bin/phantomjs %s %s %s;';
        $parameter = [
            $script,
            'localhost:1111', // TODO: test
            '/fixed/output/'.$hash
        ];
        $html = shell_exec(vsprintf($command, $parameter));
        if (empty($html)) {
            // TODO: 例外処理
            return;
        }
        file_put_contents($outHtml, $html);

        $command = <<< EOF
/usr/local/bin/wkhtmltopdf --page-size A4 --orientation landscape --encoding UTF-8 -B 1 -L 1 -R 1 -T 1 --disable-javascript --print-media-type %s %s;
EOF;
        $parameter = [
            $outHtml,
            $outPdf
        ];
        $result = shell_exec(vsprintf($command, $parameter));

        $this->response->type('pdf');
        $this->response->file($outPdf ,
            array('download'=> false, 'name'=> $hash.'.pdf'));
    }

}
