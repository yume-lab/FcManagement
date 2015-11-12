<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * TimeCardState Entity.
 *
 * @property int $id
 * @property string $alias
 * @property string $name
 * @property bool $is_deleted
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $updated
 * @property \App\Model\Entity\LatestTimeCard[] $latest_time_cards
 */
class TimeCardState extends Entity
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
