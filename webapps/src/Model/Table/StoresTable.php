<?php
namespace App\Model\Table;

use App\Model\Entity\Store;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Stores Model
 *
 * @property \Cake\ORM\Association\BelongsTo $StoreCategories
 * @property \Cake\ORM\Association\HasMany $Employees
 */
class StoresTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('stores');
        $this->displayField('name');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('StoreCategories', [
            'foreignKey' => 'store_category_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('Employees', [
            'foreignKey' => 'store_id'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->add('id', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('id', 'create');

        $validator
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        $validator
            ->requirePresence('phone_number', 'create')
            ->notEmpty('phone_number');

        $validator
            ->requirePresence('zip_code', 'create')
            ->notEmpty('zip_code');

        $validator
            ->requirePresence('address_1', 'create')
            ->notEmpty('address_1');

        $validator
            ->requirePresence('address_2', 'create')
            ->notEmpty('address_2');

        $validator
            ->requirePresence('address_3', 'create')
            ->notEmpty('address_3');

        $validator
            ->add('opened', 'valid', ['rule' => 'time'])
            ->allowEmpty('opened');

        $validator
            ->add('closed', 'valid', ['rule' => 'time'])
            ->allowEmpty('closed');

        $validator
            ->add('is_deleted', 'valid', ['rule' => 'boolean'])
            ->requirePresence('is_deleted', 'create')
            ->notEmpty('is_deleted');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['store_category_id'], 'StoreCategories'));
        return $rules;
    }
}
