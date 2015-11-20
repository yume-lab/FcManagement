<?php
namespace App\Model\Table;

use App\Model\Entity\ShiftTable;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ShiftTables Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Stores
 */
class ShiftTablesTable extends Table
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

        $this->table('shift_tables');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Stores', [
            'foreignKey' => 'store_id',
            'joinType' => 'INNER'
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
            ->add('target_ym', 'valid', ['rule' => 'numeric'])
            ->requirePresence('target_ym', 'create')
            ->notEmpty('year');

        $validator
            ->allowEmpty('body');

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
        $rules->add($rules->existsIn(['store_id'], 'Stores'));
        return $rules;
    }

    /**
     * シフトデータの更新を行います.
     * もし対象年月にデータが無ければ、新たに追加します.
     *
     * @param $storeId int 店舗ID
     * @param $targetYm int 対象年月
     * @param $body array シフトデータ
     * @return bool|\Cake\Datasource\EntityInterface
     */
    public function patch($storeId, $targetYm, $body)
    {
        $data = $this->find()
            ->where(['store_id' => $storeId])
            ->where(['target_ym' => $targetYm])
            ->first();
        if (empty($data)) {
            $data = $this->newEntity();
        }
        $record = [
            'store_id' => $storeId,
            'target_ym' => $targetYm,
            'body' => json_encode($body),
            'is_deleted' => false
        ];
        $entity = $this->patchEntity($data, $record);
        return $this->save($entity);
    }
}
