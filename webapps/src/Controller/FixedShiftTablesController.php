<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\Network\Exception\NotFoundException;

/**
 * FixedShiftTables Controller
 *
 * @property \App\Model\Table\FixedShiftTablesTable $FixedShiftTables
 */
class FixedShiftTablesController extends AppController
{

    /**
     * 確定シフトの一覧表示
     *
     * @return void
     */
    public function index()
    {
        $this->paginate = [
            'conditions' => [
                'FixedShiftTables.store_id' => parent::getCurrentStoreId()
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

        $this->set('data', $data);
        $this->set('_serialize', ['data']);
    }

}
