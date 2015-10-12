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

        $info = [
            'user' => $user,
            'stores' => $stores
        ];

        //$this->log($info);
        parent::setUser($info);
    }

}