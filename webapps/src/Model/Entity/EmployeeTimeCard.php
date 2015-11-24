<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * EmployeeTimeCard Entity.
 *
 * @property int $id
 * @property int $store_id
 * @property \App\Model\Entity\Store $store
 * @property int $employee_id
 * @property \App\Model\Entity\Employee $employee
 * @property string $worked_date
 * @property \Cake\I18n\Time $start_time
 * @property \Cake\I18n\Time $end_time
 * @property \Cake\I18n\Time $break_start_time
 * @property \Cake\I18n\Time $break_end_time
 * @property int $work_minute
 * @property int $break_minute
 * @property int $real_minute
 * @property int $total_work_minute
 * @property int $total_break_minute
 * @property int $total_real_minute
 * @property int $amount
 * @property bool $is_deleted
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $updated
 */
class EmployeeTimeCard extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false,
    ];
}
