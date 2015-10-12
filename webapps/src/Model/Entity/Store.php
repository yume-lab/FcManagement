<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Store Entity.
 *
 * @property int $id
 * @property int $store_category_id
 * @property \App\Model\Entity\StoreCategory $store_category
 * @property string $name
 * @property string $phone_number
 * @property string $zip_code
 * @property string $address_1
 * @property string $address_2
 * @property string $address_3
 * @property \Cake\I18n\Time $opened
 * @property \Cake\I18n\Time $closed
 * @property bool $is_deleted
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $updated
 * @property \App\Model\Entity\Employee[] $employees
 */
class Store extends Entity
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
