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
 * @property \App\Controller\Component\ShiftTableComponent $ShiftTable
 */
class FixedShiftTablesController extends AppController
{

    /**
     * 使用コンポーネント
     * @var array
     */
    public $components = ['ShiftTable'];


    /**
     * 初期処理.
     */
    public function initialize() {
        parent::initialize();

        $this->UserAuth->allow(['prepare']);
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
        if (!is_dir($structure)) {
            mkdir($structure, 0777, true);
        }

        $pdfPath = $structure . $ym . '.pdf';
        $this->unlinkFile($pdfPath);

        $url = sprintf('%s/fixed/prepare/%s', $this->request->host(), $hash);

        // A3縦にして、全体が表示できるように
        $command = WKHTML . '%s %s %s';
        $option = ' --page-size A3 --encoding UTF-8 -L 3 -R 3 -B 3 -T 3 ';
        $option .= ' --debug-javascript --javascript-delay 5000 --print-media-type';
        $parameter = [$option, $url, $pdfPath];
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

        $events = $this->buildEvents($data['body']);
        $resources = $this->ShiftTable->buildResources($data->store_id);
        $this->set(compact('data', 'events', 'resources'));
        $this->set('_serialize', ['data', 'employees']);
    }

    /**
     * 表示するシフト情報を生成します.
     * @param $body string JSON文字列
     * @return string 整形済みのJSON文字列
     */
    private function buildEvents($body)
    {
        $events = [];
        $shift = json_decode($body, true);
        foreach ($shift as $s) {
            unset($s['title']);
            $s['resourceId'] = $s['employeeId'];
            $events[] = $s;
        }
        return json_encode($events);
    }

    /**
     * ファイルが存在していたら削除します.
     *
     * @param $path string 対象ファイルのパス
     * @return bool 処理結果. 何もしなければfalseを返却.
     */
    private function unlinkFile($path)
    {
        return (file_exists($path)) ? unlink($path) : false;
    }
}
