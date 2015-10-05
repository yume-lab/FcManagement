<?php
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;

/**
 * 管理システムの基底Class.
 */
class AppController extends Controller
{

    /**
     * 初期処理.
     * @return void
     */
    public function initialize() {
        parent::initialize();

        $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');
    }

    /**
     * レンダリング直前処理.
     *
     * @param \Cake\Event\Event $event The beforeRender event.
     * @return void
     */
    public function beforeRender(Event $event) {
        if (!array_key_exists('_serialize', $this->viewVars) &&
            in_array($this->response->type(), ['application/json', 'application/xml'])
        ) {
            $this->set('_serialize', true);
        }
    }

    /**
     * セッションオブジェクトを取得します.
     * @return \Cake\Network\Session
     */
    protected function getSession() {
        return $this->request->session();
    }
}
