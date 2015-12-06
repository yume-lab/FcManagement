<?php
namespace App\Model\Table;

use App\Model\Entity\FixedShiftTable;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * FixedShiftTables Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Stores
 */
class FixedShiftTablesTable extends Table
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

        $this->table('fixed_shift_tables');
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
            ->notEmpty('target_ym');

        $validator
            ->requirePresence('hash', 'create')
            ->notEmpty('hash');

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
     * 指定された年月のシフトを全て削除します.
     *
     * @param $targetYm string 対象年月
     * @return \Cake\Database\StatementInterface
     */
    public function removeAllByTargetYm($targetYm)
    {
        return $this->query()
            ->update()
            ->set(['is_deleted' => true])
            ->where(['target_ym' => $targetYm])
            ->execute();
    }

    /**
     * 確定されたシフトデータを挿入します.
     *
     * @param $storeId int 店舗ID
     * @param $targetYm int 対象年月
     * @param $body array シフトデータ
     * @return bool|\Cake\Datasource\EntityInterface
     */
    public function add($storeId, $targetYm, $body)
    {
        $hash = sha1(ceil(microtime(true)*1000));
        $data = $this->newEntity();
        $record = [
            'store_id' => $storeId,
            'target_ym' => $targetYm,
            'hash' => $hash,
            'body' => json_encode($body),
            'is_deleted' => false
        ];
        $entity = $this->patchEntity($data, $record);
        return $this->save($entity);
    }

    /**
     * キーからデータを取得します.
     * @param $hash string ハッシュキー
     * @return mixed 検索結果
     */
    public function findByHash($hash)
    {
        return $this->find()
            ->where(['hash' => $hash])
            ->where(['is_deleted' => false])
            ->first();
    }
}
