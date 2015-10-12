<?php
namespace App\Controller\Component;

use Cake\Controller\Component\AuthComponent;

/**
 * ログインユーザーの認証用に拡張したAuthComponent.
 *
 *
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
        parent::setUser($user);
    }

}