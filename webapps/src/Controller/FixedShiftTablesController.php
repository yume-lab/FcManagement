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
     * @param string|null $hash シフト表のハッシュ
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     * @return void
     */
    public function view($hash = null)
    {
        parent::removeViewFrame();
        $this->setData($hash);
    }

    /**
     * PDFダウンロード処理を行います.
     * @param string|null $hash シフト表のハッシュ
     * @return void PDFファイルダウンロードのレスポンス
     */
    public function download($hash = null)
    {
        $this->autoRender = false;

        $data = $this->FixedShiftTables->findByHash($hash);
        $ym = $data->target_ym;

        // 保存先ディレクトリ作成
        $structure = TMP_PDF_SHIFT . $hash . DS;
        mkdir($structure, 0777, true);

        // 元になるHTML作成
        $script = BIN . 'OutHtml.js';
        $command = PHANTOMJS . ' %s %s %s;';
        $parameter = [$script, $this->request->host(), '/fixed/prepare/' . $hash];
        $html = shell_exec(vsprintf($command, $parameter));

        $htmlPath = $structure . $ym . '.html';
        file_put_contents($htmlPath, $html);

        // HTMLからPDFを作成
        $pdfPath = $structure . $ym . '.pdf';
        $command = <<< EOF
%s --page-size A4 --orientation landscape --encoding UTF-8 --disable-javascript --print-media-type %s %s;
EOF;
        $parameter = [WKHTML, $htmlPath, $pdfPath];
        shell_exec(vsprintf($command, $parameter));
        $this->response->type('pdf');
        $this->response->file($pdfPath,
            array('download'=> true, 'name'=> $ym.'.pdf'));
    }

    /**
     * 確定されたシフトのPDF出力用Actionです.
     * これはバッチからのみアクセスされます.
     * @param null $hash
     */
    public function prepare($hash = null)
    {
        $this->viewBuilder()->layout('Pdf/shift');
        $this->setData($hash);
        $this->render('Pdf/prepare');
    }

    /**
     * シフト表に表示するデータを画面に設定します.
     * @param $hash string ハッシュキー
     */
    private function setData($hash)
    {
        $data = $this->FixedShiftTables->findByHash($hash);
        if (empty($data)) {
            throw new NotFoundException();
        }

        /** @var \App\Model\Table\EmployeesTable $Employees */
        $Employees = TableRegistry::get('Employees');
        $employees = $Employees->findByStoreId($data->store_id);
        $this->set(compact('data', 'employees'));
        $this->set('_serialize', ['data', 'employees']);
    }

}
