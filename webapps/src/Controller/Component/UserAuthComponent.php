<?php
namespace App\Controller\Component;

use Cake\Controller\Component\AuthComponent;
use Cake\ORM\TableRegistry;

/**
 * ログインユーザーの認証用に拡張したAuthComponent.
 */
class UserAuthComponent extends AuthComponent
{

    /**
     * ストレージにユーザー情報をセットします.
     * setUser()メソッドをオーバーライドしてます.
     *
     * @see AuthComponent::setUser()
     * @param array $user
     */
    public function setUser(array $user)
    {
        // アソシエーションしてるテーブル情報も取ってくる
        $stores = TableRegistry::get('UserStores')
            ->find()
            ->where(
                ['user_id' => $user['id']]
            )
            ->contain('Roles')
            ->contain('Stores')
            ->all()
            ->toArray()
        ;

        $this->log($user);
        $this->log($stores);
        $current = array_shift($stores);
        $info = [
            'user' => $user,
            'stores' => $stores,
            'current' => $current['store']
        ];

        //$this->log($info);
        parent::setUser($info);
    }

    /**
     * 現在ログインしているユーザーの操作店舗情報を取得します.
     *
     * @see \App\Model\Table\StoresTable
     * @param $field
     * @return array|string
     */
    public function store($field = '')
    {
        $current = parent::user('current');
        return (empty($field) ? $current : $current[$field]);
    }

    /**
     * セッション内のユーザー情報を最新にします.
     */
    public function refresh()
    {
        $user = $this->identify();
        $this->setUser($user);
    }
}