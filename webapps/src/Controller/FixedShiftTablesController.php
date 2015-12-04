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


    public function initialize() {
        parent::initialize();

        $this->UserAuth->allow(['output']);
    }

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

}
