<?php
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;

/**
 * 管理システムの基底Class.
 *
 * @property      \Cake\Controller\Component\RequestHandlerComponent $RequestHandler
 * @property      \Cake\Controller\Component\FlashComponent $Flash
 * @property      \App\Controller\Component\UserAuthComponent $UserAuth
 */
class AppController extends Controller
{

    /**
     * 初期処理.
     * @return void
     */
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');
        $this->loadComponent('UserAuth', [
            'authorize' => ['Controller'],
            'authenticate' => [
                'Form' => [
                    'userModel' => 'Users',
                    'fields' => [
                        'username' => 'email',
                        'password' => 'password'
                    ]
                ]
            ],
            'loginRedirect' => [
                'controller' => 'Dashboard',
                'action' => 'index'
            ],
            'logoutRedirect' => [
                'controller' => 'Users',
                'action' => 'login'
            ],
            'authError' => 'ログインしてください。'
        ]);
    }

    /**
     * リクエスト毎の処理.
     *
     * @param Event $event
     * @return void
     */
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $user = $this->UserAuth->user();
        $this->set(compact('user'));
        $this->set('_serialize', ['user']);
    }

    /**
     * レンダリング直前処理.
     *
     * @param Event $event
     * @return void
     */
    public function beforeRender(Event $event)
    {
        if (!array_key_exists('_serialize', $this->viewVars) &&
            in_array($this->response->type(), ['application/json', 'application/xml'])
        ) {
            $this->set('_serialize', true);
        }
    }

    /**
     * 認証済チェックの基底処理を行います.
     *
     * @see \Cake\Controller\Component\AuthComponent $Auth
     * @param null $user
     * @return bool
     */
    public function isAuthorized($user = null)
    {
        return !empty($user);
    }

    /**
     * ヘッダー、フッター、サイドメニューを表示させないようにします.
     */
    protected function removeViewFrame()
    {
        $this->viewBuilder()->layout('nowrap');
    }

}
