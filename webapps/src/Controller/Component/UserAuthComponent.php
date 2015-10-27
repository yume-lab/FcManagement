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
        $data = TableRegistry::get('UserStores')
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
        $this->log($data);

        $stores = [];
        foreach ($data as $storeInfo) {
            $stores[] = $storeInfo['store'];
        }

        // TODO: 動的にできるように
        $current = array_shift($stores);
        $info = [
            'user' => $user,
            'stores' => $stores,
            'current' => $current
        ];

        //$this->log($info);
        parent::setUser($info);
    }

    /**
     * 現在ログインしているユーザーの管理店舗を取得します.
     *
     * @see \App\Model\Table\StoresTable
     * @return array|string
     */
    public function stores()
    {
        return parent::user('stores');
    }

    /**
     * 現在ログインしているユーザーの操作店舗情報を取得します.
     *
     * @see \App\Model\Table\StoresTable
     * @param $field
     * @return array|string
     */
    public function currentStore($field = '')
    {
        $current = parent::user('current');
        return (empty($field) ? $current : $current[$field]);
    }

    /**
     * 現在ログインしているユーザー情報を取得します.
     *
     * @see \App\Model\Table\Users
     * @param $field
     * @return array|string
     */
    public function user($field = '')
    {
        $user = parent::user('user');
        return (empty($field) ? $user : $user[$field]);
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