<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * LatestTimeCard Entity.
 *
 * @property int $id
 * @property int $store_id
 * @property \App\Model\Entity\Store $store
 * @property int $employee_id
 * @property \App\Model\Entity\Employee $employee
 * @property int $time_card_state_id
 * @property \App\Model\Entity\TimeCardState $time_card_state
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $updated
 */
class LatestTimeCard extends Entity
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
