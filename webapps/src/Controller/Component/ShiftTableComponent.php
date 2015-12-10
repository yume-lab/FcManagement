<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\ORM\TableRegistry;

/**
 * ShiftTable component
 * Class ShiftTableComponent
 * シフト関連のコンポーネントです.
 */
class ShiftTableComponent extends Component
{

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [];


    /**
     * リソースエリアに設定するJSONを生成します.
     *
     * @param $storeId int 店舗ID
     * @return string JSON文字列
     */
    public function buildResources($storeId)
    {
        /** @var \App\Model\Table\EmployeesTable $Employees */
        $Employees = TableRegistry::get('Employees');
        $employees = $Employees->findByStoreId($storeId);

        $resources = [];
        foreach ($employees as $employee) {
            $resources[] = [
                'id' => $employee->id,
                'title' => $employee->last_name,
            ];
        }
        return json_encode($resources);
    }

}
